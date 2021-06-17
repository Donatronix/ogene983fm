<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Contact\Contact
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contact\Contact whereUpdatedAt($value)
 */
class Contact extends Model
{
    //
}
