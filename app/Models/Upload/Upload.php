<?php

namespace App\Models\Upload;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Upload\Upload
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $file
 * @property string|null $description
 * @property string $uploadable_type
 * @property int $uploadable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $upload
 * @property-read Model|\Eloquent $uploadable
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUploadableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUploadableType($value)
 * @property string|null $title
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereTitle($value)
 */
class Upload extends Model
{
    use HasFactory;

    protected $fillable = ['file'];

    public function uploadable()
    {
        return $this->morphTo();
    }

    public function getUploadAttribute()
    {
        if ($this->attributes['file']) {
            if (file_exists(asset($this->attributes['file']))) {
                return asset($this->attributes['file']);
            }
            return asset('media/' . $this->attributes['file']);
        }
        return  null;
    }
}
