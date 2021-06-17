<?php

namespace App\Models\Category;

use App\Models\Gallery\Album;
use App\Models\Metro\Metro;
use App\Models\Post\Post;
use App\Traits\AboutTrait;
use App\Traits\HasScopeChecks;
use App\Traits\Taggable;
use App\Traits\UploadImage;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Category\Category
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Gallery\Album[] $albums
 * @property-read int|null $albums_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category\Category[] $childrenCategories
 * @property-read int|null $children_categories_count
 * @property-read \App\Models\Description\Description|null $description
 * @property-read mixed $about
 * @property-read mixed $bottom_parent
 * @property-read mixed $cover_image
 * @property-read mixed $excerpt
 * @property-read mixed $parents
 * @property-read mixed $summary
 * @property-read mixed $top_parent
 * @property-read \App\Models\Image\Image|null $image
 * @property-read \App\Models\Category\Category $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category\Category[] $subcategories
 * @property-read int|null $subcategories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category featured()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category nonParent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category\Category whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Metro[] $metroArticles
 * @property-read int|null $metro_articles_count
 */
class Category extends Model implements Searchable
{
    use AboutTrait;
    use HasScopeChecks;
    use HasSlug;
    use Taggable;
    use UploadImage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'category_id',
    ];

    protected $casts = [
        'category_id' =>  'integer',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
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
        // $url = route('category.show', $this->slug);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->id,
            null
        );
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Category\Category');
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);
        $parent = Category::find($this->category_id);
        while (!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }
        return $parents;
    }
    public function isParent()
    {
        return $this->category_id == null;
    }

    public function getTopParentAttribute()
    {
        return $this->parents->last();
    }

    public function getBottomParentAttribute()
    {
        return $this->parents->first();
    }

    /**
     * get sub categories
     */
    public function childrenCategories()
    {
        return $this->hasMany('App\Models\Category\Category')->with('categories');
    }

    /**
     * get sub categories
     */
    public function subcategories()
    {
        return $this->hasMany('App\Models\Category\Category', 'category_id');
    }

    /**
     * Fetch categories by featured.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->whereFeatured(1);
    }

    /**
     * Fetch categories by featured.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNonParent($query)
    {
        return $query->where('category_id', '<>', null);
    }

    public function metroArticles()
    {
        return $this->hasMany(Metro::class, 'category_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function albums()
    {
        return $this->hasMany(Album::class, 'category_id');
    }
}
