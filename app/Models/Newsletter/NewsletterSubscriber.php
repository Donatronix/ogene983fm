<?php

namespace App\Models\Newsletter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Newsletter\NewsletterSubscriber
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\NewsletterSubscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\NewsletterSubscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\NewsletterSubscriber query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $UUID
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\NewsletterSubscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\NewsletterSubscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\NewsletterSubscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\NewsletterSubscriber whereUUID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Newsletter\NewsletterSubscriber whereUpdatedAt($value)
 */
class NewsletterSubscriber extends Model
{
    protected $table = "newsletter_subscribers";

    protected $fillable = [
        'UUID', 'email',
    ];

    protected static function boot()
    {
        parent::boot();
        NewsletterSubscriber::saving(function ($model) {
            $model->UUID = (string) Str::random(50);
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'UUID';
    }
}
