<?php

namespace App\Models\Post;

use App\Traits\AboutTrait;
use App\Traits\Taggable;
use App\Traits\UploadImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Post\Post
 *
 * @property-read \App\Models\Presenter\Presenter $author
 * @property-read \App\Models\Category\Category $category
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $cover_image
 * @property-read mixed $excerpt
 * @property-read mixed $summary
 * @property-read \App\Models\Image\Image|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post myPosts()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $user_id
 * @property int $category_id
 * @property int $programme_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post whereProgrammeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post\Post whereUserId($value)
 */
class Post extends Model implements Searchable
{
    use AboutTrait;
    use HasSlug;
    use Taggable;
    use UploadImage;

    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('post.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->id,
            $url
        );
    }

    public function author()
    {
        return $this->belongsTo('App\Models\Presenter\Presenter');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category\Category');
    }

    public function scopeMyPosts($query)
    {
        return $query->whereHas('users', function ($query) {
            $query->where('users.id', Auth::user()->id);
        });
    }
}
