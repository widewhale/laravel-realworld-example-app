<?php

namespace App\Http\Resources\Api;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * Auth token.
     *
     * @var string
     */
    private $token;
    public static $wrap = 'user';

    /**
     * @param $resource
     * @param string $token
     */
    public function __construct($resource, string $token)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'email' => $this->email,
            'token' => $this->token,
            'username' => $this->username,
            'bio' => $this->bio,
            'image' => $this->image,
        ];
    }
}
