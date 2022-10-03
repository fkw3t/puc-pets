<?php

namespace App\Http\Controllers;

use DomainException;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Schedule\ScheduleResource;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;

class ScheduleController extends Controller
{
    public function index(): JsonResource
    {
        $schedules = Schedule::all();

        return ScheduleResource::collection($schedules);
    }

    public function open(): JsonResource
    {
        $schedules = Schedule::where('status', 'open');

        return ScheduleResource::collection($schedules);
    }

    public function pending(): JsonResource
    {
        $schedules = Schedule::where('status', 'pending');

        return ScheduleResource::collection($schedules);
    }

    public function confirmed(): JsonResource
    {
        $schedules = Schedule::where('status', 'confirmed');

        return ScheduleResource::collection($schedules);
    }

    public function canceled(): JsonResource
    {
        $schedules = Schedule::where('status', 'canceled');

        return ScheduleResource::collection($schedules);
    }

    public function show(int $id): JsonResource|JsonResponse
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            throw new DomainException('Content not found', 204);
        }

        return new ScheduleResource($schedule);
    }

    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $data = $request->only('vet_id', 'date');
        $data['status'] = 'open';
        
        Schedule::create($data);

        return response()->json([
            'message' => 'Successfully created'
        ], 201);
    }

    public function update(UpdateScheduleRequest $request, int $id): JsonResponse
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            throw new DomainException('Content not found', 204);
        }

        if( in_array($schedule->status, ['pending', 'confirmed', 'canceled']) ) {
            throw new DomainException('This schedule cannot be updated as its status is no longer open', 400);
        }

        if ($request->user()->cannot('update', $schedule)) {
            return response()->json([
                'message' => 'You can only update your own schedules'
            ], 403);
        }

        $schedule->update($request->only('date'));

        return response()->json([
            'message' => 'Successfully updated'
        ], 200);
    }

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

