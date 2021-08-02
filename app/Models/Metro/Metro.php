<?php

namespace App\Models\Metro;

use App\Models\Category\Category;
use App\Models\User;
use App\Traits\AboutTrait;
use App\Traits\Taggable;
use App\Traits\UploadFiles;
use App\Traits\UploadImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Metro\Metro
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Metro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Metro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Metro query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $author
 * @property string $content
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $cover_image
 * @property-read mixed $excerpt
 * @property-read mixed $summary
 * @property-read \App\Models\Image\Image|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Metro whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metro whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metro whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metro whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metro whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metro whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Upload\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @property int|null $category_id
 * @property-read Category|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|Metro whereCategoryId($value)
 */
class Metro extends Model implements Searchable
{
    use AboutTrait;
    use HasFactory;
    use HasSlug;
    use Taggable;
    use UploadFiles;
    use UploadImage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'slug', 'title', 'content', 'author', 'user_id'];


    /**
     * Get the options for generating the slug
     *
     * @return \Spatie\Sluggable\SlugOptions
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
    public function getRouteKeyName()
    {
        return 'slug';
    }


    /**
     * Get the search result
     *
     * @return \Spatie\Searchable\SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $url = route('metro.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    /**
     * Get the owner of the article
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return route('metro.show', $this->slug);
    }
}
