<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function authenticatedUser()
    {
        return (new UserResource(auth()->user()));
    }

    public function update(UpdateUserRequest $request)
    {
        $attributes = $request->validated();

        /** @var User $user */
        $user = $request->user();

        $user->update($attributes);

        return new UserResource($user);
    }
}
