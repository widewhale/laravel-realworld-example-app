<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @param string $username
     * @return ProfileResource
     */
    public function show(string $username)
    {
        $user = User::whereUsername($username)->firstOrFail();

        return new ProfileResource($user);
    }

    /**
     * @param \Request $request
     * @param string $username
     * @return ProfileResource
     */
    public function follow(Request $request, string $username)
    {
        $profile = User::whereUsername($username)->firstOrFail();

        $profile->followers()->syncWithoutDetaching($request->user());

        return new ProfileResource($profile);
    }

    /**
     * @param \Request $request
     * @param string $username
     * @return ProfileResource
     */
    public function unfollow(Request $request, string $username)
    {
        $profile = User::whereUsername($username)->firstOrFail();

        $profile->followers()->detach($request->user());

        return new ProfileResource($profile);
    }
}
