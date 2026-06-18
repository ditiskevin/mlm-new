<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PregnancyWeek extends Model
{
    protected $guarded = [];

    protected $casts = [
        'position' => 'integer',
        'trimester' => 'integer',
    ];
}
