<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Babysitter extends Model
{
    protected $guarded = [];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'experience_years' => 'integer',
        'age' => 'integer',
        'has_vog' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
