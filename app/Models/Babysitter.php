<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Babysitter extends Model
{
    protected $guarded = [];

    /** aanbod = offering to babysit, gezocht = a family looking for one. */
    public const TYPES = [
        'aanbod' => 'Oppas aangeboden',
        'gezocht' => 'Oppas gezocht',
    ];

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
