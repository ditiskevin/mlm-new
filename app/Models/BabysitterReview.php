<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BabysitterReview extends Model
{
    protected $guarded = [];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function babysitter(): BelongsTo
    {
        return $this->belongsTo(Babysitter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
