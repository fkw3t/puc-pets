<?php

namespace App\Http\Controllers;

use DomainException;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Pet;
use App\Http\Resources\Pet\{PetResource, PetCollection};
use App\Http\Requests\Pet\{StorePetRequest, UpdatePetRequest};

class PetController extends Controller
{
    /**
     * @OA\Get(
     *  tags={"pets"},
     *  path="/api/auth/pet",
     *  operationId="listPets",
     *  summary="list all pets in system",
     *  @OA\Response(response="200",
     *    description="Validation Response",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index(): JsonResource
    {
        $pets = Pet::all();

        return PetResource::collection($pets);
    }

    /**
     * @OA\Post(
     *  tags={"pets"},
     *  path="/api/auth/pet",
     *  operationId="registerPet",
     *  summary="register pet",
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="size",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="birth_date",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="owner_id",
     *                     type="integer"
     *                 ),
     *                 example={
     *                          "name": "Qiyana",
     *                          "size": "small",
     *                          "birth_date": "2019-01-01",
     *                          "owner_id": 1,
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
    public function store(StorePetRequest $request): JsonResponse
    {
        $data = $request->only('name', 'size', 'birth_date', 'owner_id');
        Pet::create($data);

        return response()->json([
            'message' => 'Successfully created'
        ], 201);
    }

    /**
     * @OA\Get(
     *  tags={"pets"},
     *  path="/api/auth/pet/{id}",
     *  operationId="listPetById",
     *  summary="list pet by id",
     *  @OA\Parameter(
     *         description="pet id",
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
    public function show(int $id): JsonResource
    {
        $pet = Pet::find($id);

        if (!$pet) {
            throw new DomainException('Content not found', 204);
        }

        return new PetResource($pet);
    }

    /**
     * @OA\Put(
     *  tags={"pets"},
     *  path="/api/auth/pet/{id}",
     *  operationId="updatPet",
     *  summary="update pet",
     *  @OA\Parameter(
     *         description="pet id",
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
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="size",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="birth_date",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "name": "Bidu",
     *                          "size": "medium",
     *                          "birth_date": "2018-01-01"
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
    public function update(UpdatePetRequest $request, int $id): JsonResponse
    {
        $pet = Pet::find($id);

        if (!$pet) {
            throw new DomainException('Content not found', 204);
        }

        if ($request->user()->cannot('update', $pet)) {
            return response()->json([
                'message' => 'You can only update information of pets that you own'
            ], 403);
        }

        $pet->update($request->only([
            'name',
            'size',
            'birth_date'
        ]));

        return response()->json([
            'message' => 'Successfully updated'
        ], 200);
    }

    /**
     * @OA\Delete(
     *  tags={"pets"},
     *  path="/api/auth/pet/{id}",
     *  operationId="removePet",
     *  summary="remove pet",
     *  @OA\Parameter(
     *         description="pet id",
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
        $pet = Pet::find($id);

        if (!$pet) {
            throw new DomainException('Content not found', 204);
        }

        if ($request->user()->cannot('destroy', $pet)) {
            return response()->json([
                'message' => 'You can only delete pets that you own'
            ], 403);
        }

        Pet::destroy($pet);

        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
