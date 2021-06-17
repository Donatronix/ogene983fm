<?php

namespace App\Models\Programme;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Programme\ProgrammeDay
 *
 * @property-read mixed $day
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Programme\Programme[] $programmes
 * @property-read int|null $programmes_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeDay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeDay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeDay query()
 * @mixin \Eloquent
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeDay whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeDay whereId($value)
 */
class ProgrammeDay extends Model
{
    public $timestamps = false;

    public function programmes()
    {
        return $this->belongsToMany('App\Models\Programme\Programme');
    }

    public function getDayAttribute($value)
    {
        return ucfirst(strtolower($value));
    }

    
}
