<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevealIdea extends Model
{
    protected $guarded = [];

    protected $casts = [
        'position' => 'integer',
    ];
}
