<?php

namespace App\Models\Programme;

use App\Models\Discussion\Discussion;
use App\Models\Presenter\Presenter;
use App\Models\Programme\ProgrammeTime;
use App\Models\User;
use App\Traits\AboutTrait;
use App\Traits\Taggable;
use App\Traits\UploadImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Programme\Programme
 *
 * @property-read \App\Models\Description\Description|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Discussion\Discussion[] $discussions
 * @property-read int|null $discussions_count
 * @property-read mixed $about
 * @property-read mixed $content
 * @property-read mixed $cover_image
 * @property-read mixed $excerpt
 * @property-read mixed $summary
 * @property-read \App\Models\Image\Image|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Presenter\Presenter[] $presenters
 * @property-read int|null $presenters_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Programme\ProgrammeDay[] $programmeDays
 * @property-read int|null $programme_days_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Programme\ProgrammeTime[] $programmeTimes
 * @property-read int|null $programme_times_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme myProgrammes()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme onAir()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme userProgrammes(\App\Models\User $user)
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\Programme whereUpdatedAt($value)
 */
class Programme extends Model implements Searchable
{
    use AboutTrait;
    use HasSlug;
    use Taggable;
    use UploadImage;

    protected $fillable = ['title'];

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
        $url = route('programme.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->id,
            $url
        );
    }


    /**
     * Get presenters for the programme
     *
     * @return void
     */
    public function presenters()
    {
        return $this->belongsToMany(Presenter::class);
    }

    /**
     * Get programme discussions
     *
     * @return void
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'programme_id');
    }

    /**
     * Get the programme times
     *
     * @return void
     */
    public function programmeTimes()
    {
        return $this->belongsToMany(ProgrammeTime::class);
    }

    /**
     * get the programme days
     *
     * @return void
     */
    public function programmeDays()
    {
        return $this->belongsToMany('App\Models\Programme\ProgrammeDay');
    }

    /**
     * Scope a query to only include current programme on air
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query //query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function scopeOnAir($query)
    {
        return $query->whereHas('programmeTimes', function ($query) {
            $currentTime = \intval(strtotime(Carbon::now()->toTimeString()));
            $query
                ->where('programme_times.day', Carbon::now()->englishDayOfWeek)
                ->where('programme_times.from', '<=', $currentTime)
                ->where('programme_times.to', '>', $currentTime);
        });
    }


    /**
     * Scope a query to only include my programmes
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query //query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMyProgrammes($query)
    {
        return $query->whereHas('users', function ($query) {
            $query->where('users.id', Auth::user()->id);
        });
    }

    private $user;

    /**
     * Scope a query to only include user programmes
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query //query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserProgrammes($query, User $user)
    {
        $this->user = $user;
        return $query->whereHas('users', function ($query) {
            $query->where('users.id', $this->user->id);
        });
    }

    /**
     * Get programme about
     *
     * @return void
     */
    public function getContentAttribute()
    {
        return $this->about;
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return route('programme.show', $this->slug);
    }
}
