<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    /**
     * @OA\Post(
     *  tags={"authentication"},
     *  path="/api/auth/login",
     *  operationId="login",
     *  summary="make login to get authenticated",
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@mail.com", "password": "test123"}
     *             )
     *         ),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "mail@mail.com", "password": "1234678"}
     *             )
     *         )
     *     ),
     *  @OA\Response(response="200",
     *    description="Success",
     *  ),
     *  @OA\Response(response="401",
     *    description="Invalid Credentials",
     *  ),
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if ($token = auth()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * @OA\Get(
     *  tags={"authentication"},
     *  path="/api/auth/me",
     *  operationId="authUserDetails",
     *  summary="details about signed account as token and user detail",
     *  @OA\Response(response="200",
     *    description="Success",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function me(): JsonResponse
    {
        $user = auth()->user();
        $token = auth()->tokenById($user->id);
        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *  tags={"authentication"},
     *  path="/api/auth/logout",
     *  operationId="logout",
     *  summary="logout and revoke token",
     *  @OA\Response(response="200",
     *    description="Successfully logged out",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     *  tags={"authentication"},
     *  path="/api/auth/refresh",
     *  operationId="refreshToken",
     *  summary="refresh actual token",
     *  @OA\Response(response="200",
     *    description="Success",
     *  ),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'user' => [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'document' => Auth::user()->document_id,
            ],
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
            ]
        ]);
    }
}