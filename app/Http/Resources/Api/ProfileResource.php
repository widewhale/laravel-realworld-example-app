<?php

namespace App\Http\Resources\Api;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var User|null $user */
        $user = $request->user();

        return [
            'username' => $this->username,
            'bio' => $this->bio,
            'image' => $this->image,
            'following' => $this->when($user !== null, function () use ($user) {
                $user->isFollowing($user);
            }),
        ];
    }
}
