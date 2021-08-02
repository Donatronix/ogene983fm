<?php

namespace App\Models\Gallery;

use App\Traits\AboutTrait;
use App\Traits\Taggable;
use App\Traits\UploadImage;
use Illuminate\Database\Eloquent\Model;
use Laravelista\Comments\Commentable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * App\Models\Gallery\Album
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Gallery\AlbumUpload[] $albumUploads
 * @property-read int|null $album_uploads_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravelista\Comments\Comment[] $approvedComments
 * @property-read int|null $approved_comments_count
 * @property-read \App\Models\Category\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravelista\Comments\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $cover_image
 * @property-read mixed $excerpt
 * @property-read mixed $summary
 * @property-read \App\Models\Image\Image|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\Album newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\Album query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\Album whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\Album whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\Album whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\Album whereUpdatedAt($value)
 */
class Album extends Model implements Searchable
{
    use Commentable;
    use AboutTrait;
    use HasSlug;
    use Taggable;
    use UploadImage;

    protected $fillable = [
        'id',
        'title',
        'slug',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(255);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('gallery.album.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->id,
            $url
        );
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category\Category');
    }

    public function albumUploads()
    {
        return $this->hasMany('App\Models\Gallery\AlbumUpload', 'album_id');
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return route('gallery.album.show', $this->slug);
    }
}
