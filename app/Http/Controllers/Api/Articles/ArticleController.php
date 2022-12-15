<?php

namespace App\Http\Controllers\Api\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateArticleRequest;
use App\Http\Requests\Api\ListArticleRequest;
use App\Http\Requests\Api\UpdateArticleRequest;
use App\Http\Resources\Api\ArticleResource;
use App\Models\Article;
use App\Models\User;
use App\Services\ArticleTagService;
use Illuminate\Support\Collection;

class ArticleController extends Controller
{
    /**
     * @var ArticleTagService
     */
    private $articleTagService;

    /**
     * @param ArticleTagService $articleTagService
     */
    public function __construct(ArticleTagService $articleTagService)
    {
        $this->articleTagService = $articleTagService;
    }

    /**
     * @param string $slug
     * @return ArticleResource
     */
    public function show(string $slug)
    {
        return new ArticleResource(Article::whereSlug($slug)->firstOrFail());
    }

    /**
     * @param ListArticleRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(ListArticleRequest $request)
    {
        $filters = Collection::make($request->validated());

        $query = Article::list($this->getLimit($filters), $this->getOffset($filters));

        if ($filters->has('tag')) {
            $query->hasTag($filters->get('tag'));
        }

        if ($filters->has('author')) {
            $query->createdByUser($filters->get('author'));
        }

        if ($filters->has('favorited')) {
            $query->favoritedByUser($filters->get('favorited'));
        }

        return ArticleResource::collection($query->get());
    }

    /**
     * @param ListArticleRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function feed(ListArticleRequest $request)
    {
        $filters = Collection::make($request->validated());

        $query = Article::list($this->getLimit($filters), $this->getOffset($filters));
        $query->followedOfUser($request->user());

        return ArticleResource::collection($query->get());
    }

    /**
     * @param CreateArticleRequest $request
     * @return ArticleResource
     */
    public function create(CreateArticleRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $attributes = $request->validated();
        $attributes['article']['author_id'] = $user->getKey();
        $article = Article::create($attributes['article']);

        $tags = \Arr::pull($attributes, 'tagList');

        if (is_array($tags)) {
            $this->articleTagService->attachTags($article, $tags);
        }

        return new ArticleResource($article);
    }

    /**
     * @param UpdateArticleRequest $request
     * @param string $slug
     * @return ArticleResource
     */
    public function update(UpdateArticleRequest $request, string $slug)
    {
        $article = Article::whereSlug($slug)->firstOrFail();

        $article->update($request->validated());

        return new ArticleResource($article);
    }

    /**
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $slug)
    {
        $article = Article::whereSlug($slug)->firstOrFail();

        $article->delete();

        return response()->json([
            'message' => 'Article deleted',
        ]);
    }

    /**
     * @param Collection $filters
     * @return int
     */
    private function getLimit(Collection $filters): int
    {
        return $filters->get('limit', 20);
    }

    /**
     * @param Collection $filters
     * @return int
     */
    private function getOffset(Collection $filters): int
    {
        return $filters->get('offset', 0);
    }
}
