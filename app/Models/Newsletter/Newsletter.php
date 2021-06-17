<?php

namespace App\Models\Newsletter;

use App\Traits\AboutTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Newsletter\Newsletter
 *
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $content
 * @property-read mixed $excerpt
 * @property-read mixed $summary
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $subject
 * @property string $slug
 * @property string $message
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\Newsletter whereUpdatedAt($value)
 */
class Newsletter extends Model implements Searchable
{
    use AboutTrait;
    use HasSlug;

    protected $fillable = ['title', 'slug'];

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

    public function getContentAttribute()
    {
        return $this->about;
    }
}
