<?php

namespace App\Http\Resources\Api;

use App\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Article
 */
class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = $request->user();

        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'body' => $this->body,
            'tagList' => TagResource::collection($this->tags),
            'favorited' => $this->when($user !== null, function () use ($user) {
                $this->isFavoriteBy($user);
            }),
            'favoritesCount' => $this->favoriteUsers->count(),
            'author' => new ProfileResource($this->author),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
