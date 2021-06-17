<?php

namespace App\Traits;

use App\Models\Description\Description;
use Illuminate\Support\Facades\DB;

/**
 * Trait AboutTrait
 * @package App\Traits
 */
trait AboutTrait
{

    /**
     * Undocumented function
     *
     * @param string $description //description
     *
     * @return mixed
     */
    public function storeAbout(string $description)
    {
        DB::transaction(function () use ($description) {
            $success = false;
            if ($this->about) {
                $this->description()->update(['body' => $description]);
                $success = true;
            }
            if (!$success) {
                $describe = new Description;
                $describe->body = $description;
                $this->description()->save($describe);
            }
        });

        return $this;
    }

    /**
     * Delete description
     *
     * @return mixed
     */
    public function deleteAbout()
    {
        DB::transaction(function () {
            $this->description()->delete();
        });
        return $this;
    }

    /**
     * Get the model's about.
     *
     * @return void
     */
    public function description()
    {
        return $this->morphOne(Description::class, 'described');
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAboutAttribute()
    {
        return $this->description->body ?? null;
    }
}
