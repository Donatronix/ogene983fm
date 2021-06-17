<?php

namespace App\Models\Discussion;

use App\Traits\AboutTrait;
use App\Traits\Taggable;
use App\Traits\UploadFiles;
use App\Traits\UploadImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravelista\Comments\Commentable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Discussion\Discussion
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravelista\Comments\Comment[] $approvedComments
 * @property-read int|null $approved_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravelista\Comments\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $content
 * @property-read mixed $excerpt
 * @property-read mixed $presenters
 * @property-read mixed $programme_name
 * @property-read mixed $summary
 * @property-read \App\Models\Presenter\Presenter $presenter
 * @property-read \App\Models\Programme\Programme $programme
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion myThreads()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $programme_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion whereProgrammeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discussion\Discussion whereUpdatedAt($value)
 * @property-read mixed $cover_image
 * @property-read \App\Models\Image\Image|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Upload\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @method static \Illuminate\Database\Eloquent\Builder|Discussion whereContent($value)
 */
class Discussion extends Model implements Searchable
{
    use Commentable;
    use AboutTrait;
    use HasSlug;
    use Taggable;
    use UploadImage;
    use UploadFiles;

    protected $fillable = [
        'content',
        'conversation_id',
        'id',
        'presenter_id',
        'slug',
        'title',
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
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('discussion.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->id,
            $url
        );
    }

    public function presenter()
    {
        return $this->belongsTo('App\Models\Presenter\Presenter');
    }

    public function programme()
    {
        return $this->belongsTo('App\Models\Programme\Programme');
    }

    public function getProgrammeNameAttribute()
    {
        return $this->programme->title;
    }

    public function getPresentersAttribute()
    {
        return $this->programme->presenters;
    }

    public function scopeMyThreads($query)
    {
        return $query->whereHas('users', function ($query) {
            $query->where('users.id', Auth::user()->id);
        });
    }

    public function getContentAttribute()
    {
        return $this->about;
    }
}
