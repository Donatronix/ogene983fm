<?php

namespace App\Models\Tag;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tag\Taggable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Taggable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Taggable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Taggable query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $taggable_type
 * @property int $taggable_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Taggable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Taggable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Taggable whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Taggable whereTaggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Taggable whereTaggableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag\Taggable whereUpdatedAt($value)
 */
class Taggable extends Model
{
    //
}
