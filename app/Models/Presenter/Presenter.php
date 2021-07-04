<?php

namespace App\Models\Presenter;

use App\Models\Programme\Programme;
use App\Traits\AboutTrait;
use App\Traits\Taggable;
use App\Traits\UploadImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Presenter\Presenter
 *
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $content
 * @property-read mixed $cover_image
 * @property-read mixed $excerpt
 * @property-read mixed $summary
 * @property-read \App\Models\Image\Image|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Programme\Programme[] $programmes
 * @property-read int|null $programmes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Presenter\Presenter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Presenter\Presenter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Presenter\Presenter query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Presenter\Presenter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Presenter\Presenter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Presenter\Presenter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Presenter\Presenter whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Presenter\Presenter whereUpdatedAt($value)
 */
class Presenter extends Model implements Searchable
{
    use AboutTrait;
    use HasSlug;
    use Taggable;
    use UploadImage;

    protected $fillable = ['name'];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
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
     * Get search result
     *
     * @return \Spatie\Searchable\SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $url = route('presenter.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->id,
            $url
        );
    }

    /**
     * Get programmes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function programmes(): BelongsToMany
    {
        return $this->belongsToMany(Programme::class);
    }

    public function getContentAttribute()
    {
        return $this->about;
    }
}
