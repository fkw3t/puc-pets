<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use DomainException;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Notifications\Schedules\Canceled;
use App\Notifications\Schedules\Confirmed;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Schedules\Confirmation;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Schedule\ScheduleResource;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\AssignScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;

class ScheduleController extends Controller
{
    /**
     * @OA\Get(
     *  tags={"schedules"},
     *  path="/api/auth/schedule",
     *  operationId="listSchedules",
     *  summary="list all schedules status",
     *  @OA\Response(response="200",
     *    description="Validation Response",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index(): JsonResource
    {
        $schedules = Schedule::all();

        return ScheduleResource::collection($schedules);
    }

    /**
     * @OA\Get(
     *  tags={"schedules"},
     *  path="/api/auth/schedule/{id}",
     *  operationId="searchSchedule",
     *  summary="search schedule by id",
     *  @OA\Parameter(
     *         description="schedule id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *  @OA\Response(response="200",
     *    description="Success",
     *  ),
     *  @OA\Response(response="204",
     *    description="Content not found",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function show(int $id): JsonResource|JsonResponse
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            throw new DomainException('Content not found', 204);
        }

        return new ScheduleResource($schedule);
    }

    /**
     * @OA\Post(
     *  tags={"schedules"},
     *  path="/api/auth/schedule",
     *  operationId="createSchedule",
     *  summary="create schedule",
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="vet_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="date",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "vet_id": 1,
     *                          "date": "01-01-2025 12:00"
     *                  }
     *             )
     *         )
     *     ),
     *  @OA\Response(response="201",
     *    description="Success",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $data = $request->only('date', 'service', 'client_id', 'pet_id', 'vet_id');
        $data['date'] = new Datetime($data['date']);
        $data['status'] = 'pending';

        $schedule = Schedule::create($data);

        $confirmationUrl = URL::temporarySignedRoute(
            'schedule.confirm', now()->addMinutes(60), ['id' => $schedule->id]
        );

        Notification::send($schedule->client, new Confirmation($schedule, $confirmationUrl));

        return response()->json([
            'message' => 'Successfully created'
        ], 201);
    }

    public function confirm(int $id): JsonResponse
    {
        $schedule = Schedule::find($id);

        if( !$schedule ){
            throw new DomainException('Content not found', 204);
        }

        if( !in_array($schedule->status, ['pending']) ) {
            return response()->json([
                'message' =>  'This schedule cannot be confirmed as its status is no longer pending'
            ], 400);
            // throw new DomainException('This schedule cannot be confirmed as its status is no longer pending', 400);
        }

        $schedule->status = 'confirmed';
        $schedule->save();

        Notification::send([
            $schedule->client,
            $schedule->vet
        ], new Confirmed($schedule));

        return response()->json([
            'message' => 'Schedule successfully confirmed'
        ], 200);
    }

    /**
     * @OA\Put(
     *  tags={"schedules"},
     *  path="/api/auth/schedule/{id}",
     *  operationId="updateSchedule",
     *  summary="update schedule",
     *  @OA\Parameter(
     *         description="schedule id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="date",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "date": "01-01-2025 15:00",
     *                  }
     *             )
     *         )
     *     ),
     *  @OA\Response(response="200",
     *    description="Successfully updated",
     *  ),
     * security={{ "apiAuth": {} }}
     * )
     */
    public function update(UpdateScheduleRequest $request, int $id): JsonResponse
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            throw new DomainException('Content not found', 204);
        }

        $date = new Datetime($request->only('date')['date']);
        $schedule->update(['date' => $date]);

        return response()->json([
            'message' => 'Successfully updated'
        ], 200);
    }

    /**
     * @OA\Delete(
     *  tags={"schedules"},
     *  path="/api/auth/schedule/{id}",
     *  operationId="removeSchedule",
     *  summary="remove schedule",
     *  @OA\Parameter(
     *         description="schedule id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *  @OA\Response(response="200",
     *    description="Successfully deleted",
     *  ),
     *  @OA\Response(response="204",
     *    description="Content not found",
     *  ),
     *  @OA\Response(response="400",
     *    description="This schedule cannot be deleted as its status is no longer open",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            throw new DomainException('Content not found', 204);
        }

        if( in_array($schedule->status, ['pending', 'confirmed', 'canceled']) ) {
            throw new DomainException('This schedule cannot be deleted as its status is no longer open', 400);
        }

        if ($request->user()->cannot('destroy', $schedule)) {
            return response()->json([
                'message' => 'You can only delete your own schedule'
            ], 403);
        }

        Schedule::destroy($schedule);

        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }

}

