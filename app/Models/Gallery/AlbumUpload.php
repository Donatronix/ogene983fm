<?php

namespace App\Models\Gallery;

use App\Traits\AboutTrait;
use App\Traits\UploadImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Laravelista\Comments\Commentable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Gallery\AlbumUpload
 *
 * @property-read \App\Models\Gallery\Album $album
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravelista\Comments\Comment[] $approvedComments
 * @property-read int|null $approved_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravelista\Comments\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $cover_image
 * @property-read mixed $excerpt
 * @property-read mixed $item
 * @property-read mixed $summary
 * @property-read \App\Models\Image\Image|null $image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\AlbumUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\AlbumUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\AlbumUpload query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $album_id
 * @property string $title
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\AlbumUpload whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\AlbumUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\AlbumUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\AlbumUpload whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\AlbumUpload whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gallery\AlbumUpload whereUpdatedAt($value)
 */
class AlbumUpload extends Model implements Searchable
{
    use Commentable;
    use AboutTrait;
    use HasSlug;
    use UploadImage;

    protected $fillable = [
        'id',
        'title',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();
        Album::deleting(function ($model) {
            $albumUploads = $model->albumUploads;
            foreach ($albumUploads as $key => $upload) {
                DB::beginTransaction();
                try {
                    $upload->deleteAbout();
                    $upload->deleteItem();
                    $upload->delete();
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
                DB::commit();
            }
        });
    }

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
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->id,
            null
        );
    }

    public function getItemAttribute()
    {
        return $this->image->image ? asset($this->image->image) : null;
    }

    public function uploadItem(UploadedFile $file, $folder = null, $filename = null)
    {
        return $this->uploadImage($file, $folder, $filename);
    }

    public function updateItem(UploadedFile $file, $folder = null, $filename = null)
    {
        return $this->updateImage($file, $folder, $filename);
    }

    public function deleteItem()
    {
        return $this->deleteImage();
    }

    public function album()
    {
        return $this->belongsTo('App\Models\Gallery\Album');
    }
}
