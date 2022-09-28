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
    public function index(): JsonResource
    {
        $vets = Vet::all();

        return new VetCollection($vets);
    }

    public function store(StoreVetRequest $request): JsonResponse
    {
        $data = $request->only('user_id', 'crm', 'specialization');
        Vet::create($data);

        return response()->json([
            'message' => 'Successfully created'
        ], 201);
    }

    public function show(int $id): JsonResource|JsonResponse
    {
        $vet = Vet::find($id);

        if (!$vet) {
            throw new DomainException('Content not found', 204);
        }

        return new VetResource($vet);
    }

    public function showByCRM(string $document): JsonResource
    {
        $vet = Vet::firstWhere('crm', $document);

        if (!$vet) {
            throw new DomainException('Content not found', 204);
        }

        return new VetResource($vet);
    }

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
