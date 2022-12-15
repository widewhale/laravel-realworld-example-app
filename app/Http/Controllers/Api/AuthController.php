<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateUserRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Authenticates user.
     *
     * @param LoginRequest $request
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function authentication(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = auth()->attempt($credentials['user'])) {
            return $this->errorResponse(['Unauthorized'], 401);
        }

        return (new UserResource(auth()->user(), $token));
    }

    /**
     * @param CreateUserRequest $request
     * @return UserResource
     */
    public function registration(CreateUserRequest $request)
    {
        $attributes = $request->validated()['user'];

        $attributes['password'] = Hash::make($attributes['password']);

        $user = User::create($attributes);

        $token = auth()->login($user);

        return (new UserResource($user));
    }
}
