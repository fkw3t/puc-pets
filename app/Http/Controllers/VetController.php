<?php

namespace App\Http\Controllers;

use App\Models\Vet;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Vet\VetResource;
use App\Http\Resources\Vet\VetCollection;
use App\Http\Requests\Vet\StoreVetRequest;
use App\Http\Requests\Vet\UpdateVetRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class VetController extends Controller
{

    /**
     * @OA\Get(
     *  tags={"vets"},
     *  path="/api/auth/vet",
     *  operationId="listVets",
     *  summary="list all vets in system",
     *  @OA\Response(response="200",
     *    description="Validation Response",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index(): JsonResource
    {
        $vets = Vet::all();

        return new VetCollection($vets);
    }

    /**
     * @OA\Post(
     *  tags={"vets"},
     *  path="/api/auth/vet",
     *  operationId="createVet",
     *  summary="register user as vet",
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="crm",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="specialization",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "user_id": 1,
     *                          "crm": "031237",
     *                          "specialization": "Animal food"
     *                  }
     *             )
     *         )
     *     ),
     *  @OA\Response(response="200",
     *    description="Success",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function store(StoreVetRequest $request): JsonResponse
    {
        $data = $request->only('user_id', 'crm', 'specialization');
        Vet::create($data);

        return response()->json([
            'message' => 'Successfully created'
        ], 201);
    }

    /**
     * @OA\Get(
     *  tags={"vets"},
     *  path="/api/auth/vet/{id}",
     *  operationId="listVetById",
     *  summary="list vet by id",
     *  @OA\Parameter(
     *         description="vet id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
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
        $vet = Vet::find($id);

        if (!$vet) {
            throw new DomainException('Content not found', 204);
        }

        return new VetResource($vet);
    }

    /**
     * @OA\Get(
     *  tags={"vets"},
     *  path="/api/auth/vet/document/{document}",
     *  operationId="listUserByCRM",
     *  summary="list vet by CRM",
     *  @OA\Parameter(
     *         description="vet CRM",
     *         in="path",
     *         name="document",
     *         required=true,
     *         @OA\Schema(type="string")
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
    public function showByCRM(string $document): JsonResource
    {
        $vet = Vet::firstWhere('crm', $document);

        if (!$vet) {
            throw new DomainException('Content not found', 204);
        }

        return new VetResource($vet);
    }

    /**
     * @OA\Put(
     *  tags={"vets"},
     *  path="/api/auth/vet/{id}",
     *  operationId="updateVet",
     *  summary="update vet account details",
     *  @OA\Parameter(
     *         description="vet id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="crm",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="specialization",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "crm": "0123732",
     *                          "specialization": "Animal food"
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
    public function update(UpdateVetRequest $request, int $id): JsonResponse
    {
        $vet = Vet::find($id);

        if (!$vet) {
            throw new DomainException('Content not found', 204);
        }

        // if ($request->user()->cannot('destroy', $user)) {
        //     return response()->json([
        //         'message' => 'You can only update your own information'
        //     ], 403);
        // }

        $vet->update($request->only([
            'crm',
            'specialization'
        ]));

        return response()->json([
            'message' => 'Successfully updated'
        ], 200);
    }

    /**
     * @OA\Delete(
     *  tags={"vets"},
     *  path="/api/auth/vet/{id}",
     *  operationId="removeVet",
     *  summary="remove user as vet account",
     *  @OA\Parameter(
     *         description="vet id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *  @OA\Response(response="200",
     *    description="Successfully deleted",
     *  ),
     *  @OA\Response(response="204",
     *    description="Content not found",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $vet = Vet::find($id);

        if (!$vet) {
            throw new DomainException('Content not found', 204);
        }

        // if ($request->user()->cannot('destroy', $user)) {
        //     return response()->json([
        //         'message' => 'You can only delete your own account'
        //     ], 403);
        // }

        Vet::destroy($vet);

        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
