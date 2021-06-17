<?php

namespace App\Models;

use App\Models\Metro\Metro;
use App\Models\Post\Post;
use App\Models\Programme\Programme;
use App\Traits\AboutTrait;
use App\Traits\UploadImage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravelista\Comments\Commenter;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravelista\Comments\Comment[] $approvedComments
 * @property-read int|null $approved_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravelista\Comments\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $cover_image
 * @property-read mixed $excerpt
 * @property-read mixed $is_admin
 * @property-read mixed $is_fan
 * @property-read mixed $is_owner
 * @property-read mixed $is_presenter
 * @property-read mixed $is_super_admin
 * @property-read mixed $summary
 * @property-read \App\Models\Image\Image|null $image
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Programme\Programme[] $programmes
 * @property-read int|null $programmes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User admins()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User fans()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User presenters()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User superAdmins()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUsername($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Metro[] $metroArticles
 * @property-read int|null $metro_articles_count
 */
class User extends Authenticatable implements Searchable
{
    use Commenter;
    use AboutTrait;
    use HasRoles;
    use Notifiable;
    use UploadImage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        $users = self::whereNull('slug')->get();
        if ($users) {
            foreach ($users as $user) {
                $user->slug = Str::random(40);
                $user->save();
            }
        }
        self::saving(function ($model) {
            $model->slug = Str::random(40);
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get search result
     *
     * @return \Spatie\Searchable\SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $url = route('presenter.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name,
            $url
        );
    }

    public function programmes()
    {
        return $this->belongsToMany(Programme::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'presenter_id');
    }

    public function metroArticles()
    {
        return $this->hasMany(Metro::class, 'user_id');
    }

    public function getIsAdminAttribute()
    {
        return (bool) $this->hasRole('admin');
    }

    public function getIsSuperAdminAttribute()
    {
        return (bool) $this->hasRole('super admin');
    }

    public function getIsPresenterAttribute()
    {
        return (bool) $this->hasRole('presenter');
    }

    public function getIsFanAttribute()
    {
        return (bool) $this->hasRole('fan');
    }

    public function getIsOwnerAttribute()
    {
        return auth()->user()->id == $this->user->id;
    }

    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.name', 'admin');
        });
    }

    public function scopeSuperAdmins($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.name', 'super admin');
        });
    }

    public function scopeFans($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.name', 'fan');
        });
    }

    public function scopePresenters($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.name', 'presenter');
        });
    }
}
