<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property int $author_id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $favoriteUsers
 * @property-read int|null $favorite_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static Builder|Article createdByUser(string $username)
 * @method static Builder|Article hasTag(string $tag)
 * @method static Builder|Article list(int $limit, int $offset)
 * @method static Builder|Article favoritedByUser(string $username)
 * @method static Builder|Article followedOfUser(\App\Models\User $user)
 */
class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'author_id',
        'slug',
        'title',
        'description',
        'body',
    ];

    /**
     * Get comments for the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get tags for the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get author of the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Users that added article to the favorites list.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'article_favorite');
    }

    /**
     * Checks if user is added article to the favorites list.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function isFavoriteBy(User $user): bool
    {
        return $this->favoriteUsers()
            ->whereKey($user->getKey())
            ->exists();
    }

    /**
     * Base scope for lists.
     *
     * @param Builder $query
     * @param int $limit
     * @param int $offset
     * @return Builder
     */
    public function scopeList(Builder $query, int $limit, int $offset)
    {
        return $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->offset($offset);
    }

    /**
     * @param Builder $query
     * @param string $tag
     * @return Builder
     */
    public function scopeHasTag(Builder $query, string $tag)
    {
        return $query->whereHas('tags', function (Builder $query) use ($tag) {
            $query->where('name', $tag);
        });
    }

    /**
     * @param Builder $query
     * @param string $username
     * @return Builder
     */
    public function scopeCreatedByUser(Builder $query, string $username)
    {
        return $query->whereHas('author', function (Builder $query) use ($username) {
            $query->where('username', $username);
        });
    }

    /**
     * Scope to articles favorited by username.
     *
     * @param Builder $query
     * @param string $username
     * @return Builder
     */
    public function scopeFavoritedByUser(Builder $query, string $username)
    {
        return $query->whereHas('favoriteUser', function (Builder $query) use ($username) {
            $query->where('username', $username);
        });
    }

    /**
     * Scope to articles of users which are followed by a given user.
     *
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeFollowedOfUser(Builder $query, User $user)
    {
        return $query->whereHas('author', function (Builder $query) use ($user) {
            $query->whereIn('id', $user->authors->pluck('id'));
        });
    }
}
