<?php

namespace App\Traits;

use App\Models\Tag\Tag;

/**
 * Trait DescribeAble
 * @package App\Traits
 */
trait Taggable
{

    public function attachTags(array $tags)
    {
        foreach ($tags as $key => $tag) {
            $newTag = Tag::firstOrCreate(array('name' => strtolower($tag)));
            $this->tags()->attach($newTag->id);
        }
    }

    public function syncTags(array $tags)
    {
        $ids = array();
        foreach ($tags as $key => $tag) {
            $newTag = Tag::firstOrCreate(array('name' => strtolower($tag)));
            $ids[] = $newTag->id;
        }
        $this->tags()->sync($ids);
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag\Tag', 'taggable');
    }
}
