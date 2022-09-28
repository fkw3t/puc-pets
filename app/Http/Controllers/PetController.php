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
    public function index(): JsonResource
    {
        $pets = Pet::all();

        return new PetCollection($pets);
    }

    public function store(StorePetRequest $request): JsonResponse
    {
        $data = $request->only('name', 'size', 'type', 'owner_id');
        Pet::create($data);

        return response()->json([
            'message' => 'Successfully created'
        ], 201);
    }

    public function show(int $id): JsonResource
    {
        $pet = Pet::find($id);

        if (!$pet) {
            throw new DomainException('Content not found', 204);
        }

        return new PetResource($pet);
    }

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
            'type'
        ]));

        return response()->json([
            'message' => 'Successfully updated'
        ], 200);
    }

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
