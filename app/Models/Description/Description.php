<?php

namespace App\Models\Description;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * App\Models\Description\Description
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $described
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Description\Description newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Description\Description newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Description\Description query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $body
 * @property string $described_type
 * @property int $described_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Description\Description whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Description\Description whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Description\Description whereDescribedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Description\Description whereDescribedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Description\Description whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Description\Description whereUpdatedAt($value)
 */
class Description extends Model implements Searchable
{
    protected $table = 'descriptions';

    public function described()
    {
        return $this->morphTo();
    }

    public function getSearchResult(): SearchResult
    {
        $url = null;
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->id,
            $url
        );
    }
}
