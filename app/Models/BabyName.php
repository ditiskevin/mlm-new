<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BabyName extends Model
{
    protected $guarded = [];

    public function favouritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
