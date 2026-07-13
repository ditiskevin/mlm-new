<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * A gentle, supportive milestone a member can earn (e.g. "Nieuw lid",
 * "Eerste bericht"). Badges are non-competitive — they celebrate taking
 * part, never rank members against each other.
 */
class Badge extends Model
{
    protected $guarded = [];

    /** Members who have earned this badge. */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')->withTimestamps();
    }
}
