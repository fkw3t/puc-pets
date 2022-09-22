<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResource
    {
        $users = $this->service->index();

        return new UserCollection($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        if ($this->service->store($request)){
            return response()->json([
                'message' => 'Successfully created'
            ], 201);
        }

        return response()->json([
            'message' => ' Error to process entity'
        ], 500);
    }

    public function show(string $id): JsonResource|JsonResponse
    {
        try {
            $user = $this->service->show($id);
            return new UserResource($user);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function showByDocument(string $document): JsonResource
    {
        // TODO add service layer and filter exceptions
        $user = User::firstWhere('document_id', $document);

        if (!$user) {
            throw new DomainException('Content not found', 204);
        }

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        // TODO add service layer and filter exceptions
        $user = User::find($id);

        if (!$user) {
            throw new DomainException('Content not found', 204);
        }

        if ($request->user()->cannot('destroy', $user)) {
            return response()->json([
                'message' => 'You can only update your own information'
            ], 403);
        }

        $user->update($request->only([
            'name',
            'email',
            'phone',
            'password'
        ]));


        return response()->json([
            'message' => 'Successfully updated'
        ], 200);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        // TODO add service layer and filter exceptions
        $user = User::find($id);

        if (!$user) {
            throw new DomainException('Content not found', 204);
        }

        if ($request->user()->cannot('destroy', $user)) {
            return response()->json([
                'message' => 'You can only delete your own account'
            ], 403);
        }

        User::destroy($user);

        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
