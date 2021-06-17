<?php

namespace App\Models\Tag;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Tag\Tag
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Gallery\Album[] $albums
 * @property-read int|null $albums_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Discussion\Discussion[] $discussions
 * @property-read int|null $discussions_count
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Presenter\Presenter[] $presenters
 * @property-read int|null $presenters_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Programme\Programme[] $programmes
 * @property-read int|null $programmes_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Tag query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Tag whereUpdatedAt($value)
 */
class Tag extends Model implements Searchable
{
    use HasSlug;

    protected $fillable = ['name'];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
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
        $url = route('tag.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->id,
            $url
        );
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Get all of the challenges that are assigned this tag.
     */
    public function albums()
    {
        return $this->morphedByMany('App\Models\Gallery\Album', 'taggable');
    }

    /**
     * Get all of the challenges that are assigned this tag.
     */
    public function categories()
    {
        return $this->morphedByMany('App\Models\Category\Category', 'taggable');
    }

    /**
     * Get all of the challenges that are assigned this tag.
     */
    public function posts()
    {
        return $this->morphedByMany('App\Models\Post\Post', 'taggable');
    }

    /**
     * Get all of the challenges that are assigned this tag.
     */
    public function discussions()
    {
        return $this->morphedByMany('App\Models\Discussion\Discussion', 'taggable');
    }

    /**
     * Get all of the challenges that are assigned this tag.
     */
    public function programmes()
    {
        return $this->morphedByMany('App\Models\Programme\Programme', 'taggable');
    }

    /**
     * Get all of the challenges that are assigned this tag.
     */
    public function presenters()
    {
        return $this->morphedByMany('App\Models\Presenter\Presenter', 'taggable');
    }
}
