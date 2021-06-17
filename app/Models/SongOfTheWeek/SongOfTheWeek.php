<?php

namespace App\Models\SongOfTheWeek;

use App\Traits\AboutTrait;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\SongOfTheWeek\SongOfTheWeek
 *
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $album_art
 * @property-read mixed $excerpt
 * @property-read mixed $song
 * @property-read mixed $summary
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek currentSong()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $artist
 * @property string $album
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek whereAlbum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek whereAlbumArt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek whereArtist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek whereSong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SongOfTheWeek\SongOfTheWeek whereUpdatedAt($value)
 */
class SongOfTheWeek extends Model implements Searchable
{
    use AboutTrait;
    use HasSlug;

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

    public function getSearchResult(): SearchResult
    {
        $programmeUrl = route('programme.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title,
            $programmeUrl
        );
    }

    /**
     * get the current song of the week
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentSong($query)
    {
        $period = CarbonImmutable::now();
        return $query->whereBetween('created_at', [$period->startOfWeek(), $period->endOfWeek()]);
    }
}
