<?php

namespace App\Models\Image;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Image\Image
 *
 * @property-read mixed $image
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $imageable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image\Image query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $imageable_type
 * @property int $imageable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image\Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image\Image whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image\Image whereImageableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image\Image whereImageableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image\Image whereUpdatedAt($value)
 */
class Image extends Model
{
    protected $fillable = ['image'];

    /**
     * Imageable function
     *
     * @return void
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * Get the image path
     *
     * @param string $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        if (file_exists(asset($value))) {
            return asset($value);
        }
        return asset('media/' . $value);
    }
}
