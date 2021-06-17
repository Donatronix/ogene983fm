<?php

namespace App\Models\Programme;

use carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Programme\ProgrammeTime
 *
 * @property-read mixed $from
 * @property-read mixed $to
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Programme\Programme[] $programmes
 * @property-read int|null $programmes_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeTime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeTime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeTime onAir()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeTime query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $day
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeTime whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeTime whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Programme\ProgrammeTime whereTo($value)
 */
class ProgrammeTime extends Model
{
    public $timestamps = false;
    protected $fillable = ['day', 'from', 'to'];

    public function programmes()
    {
        return $this->belongsToMany('App\Models\Programme\Programme');
    }

    /**
     * Get the start time by converting timestamp to readable time
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getFromAttribute($value): string
    {
        return Carbon::createFromTimestamp($value)->toTimeString();
    }

    /**
     * Get the end time by converting timestamp to readable time
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getToAttribute($value): string
    {
        return Carbon::createFromTimestamp($value)->toTimeString();
    }

    /**
     * Scope a query to only include the current programme on Air
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnAir($query)
    {
        $currentTime = \intval(strtotime(Carbon::now()->toTimeString()));
        return     $query->where('day', Carbon::now()->englishDayOfWeek)
            ->where('from', '<=', $currentTime)
            ->where('to', '>', $currentTime);
    }
}
