<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChecklistItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'position' => 'integer',
    ];

    public function checkedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
