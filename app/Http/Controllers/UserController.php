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
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="PUC PETS - docs"
 * )
 */
final class UserController extends Controller
{

    /**
     * @OA\Get(
     *  tags={"users"},
     *  path="/api/auth/user",
     *  operationId="listUsers",
     *  summary="list all users in system",
     *  @OA\Response(response="200",
     *    description="Validation Response",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function index(): JsonResource
    {
        $users = User::all();

        return new UserCollection($users);
    }

    /**
     * @OA\Post(
     *  tags={"authentication"},
     *  path="/api/auth/register",
     *  operationId="create account",
     *  summary="create account to access the system",
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="document_id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "name": "Roberto Augusto",
     *                          "document_id": "123456789012",
     *                          "email": "mail@mail.com",
     *                          "phone": "(31) 91234-5678",
     *                          "password": 1234678
     *                  }
     *             )
     *         )
     *     ),
     *  @OA\Response(response="200",
     *    description="Success",
     *  )
     * )
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return response()->json([
            'message' => 'Successfully created'
        ], 201);
    }

    /**
     * @OA\Get(
     *  tags={"users"},
     *  path="/api/auth/user/{id}",
     *  operationId="listUserById",
     *  summary="list user by id",
     *  @OA\Parameter(
     *         description="user id",
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
        $user = User::find($id);

        if (!$user) {
            throw new DomainException('Content not found', 204);
        }

        return new UserResource($user);
    }

    public function showByDocument(string $document): JsonResource
    {
        $user = User::firstWhere('document_id', $document);

        if (!$user) {
            throw new DomainException('Content not found', 204);
        }

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
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

    public function destroy(Request $request, int $id): JsonResponse
    {
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

    public function pets(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            throw new DomainException('Content not found', 204);
        }

        return response()->json([
            'data' => $user->pets
        ], 200);
    }
}
