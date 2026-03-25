<?php

namespace App\Models;

trait MediaableTrait
{
    /**
     * Get all media attached to the model.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
