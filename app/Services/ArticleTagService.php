<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Tag;

class ArticleTagService
{
    /**
     * @var Article
     */
    private $article;
    /**
     * @var Tag
     */
    private $tag;

    /**
     * @param Article $article
     * @param Tag $tag
     */
    public function __construct(Article $article, Tag $tag)
    {
        $this->article = $article;
        $this->tag = $tag;
    }

    /**
     * Attaches tags to articles. Create new if tag is not exists.
     *
     * @param Article $article
     * @param array<int, string> $tags
     * @return void
     */
    public function attachTags(Article $article, array $tags)
    {
        $ids = [];

        foreach ($tags as $tag) {
            $tag = $this->tag->firstOrCreate(['name' => $tag]);
            $ids[] = $tag->id;
        }

        $article->tags()->sync($ids);
    }
}
