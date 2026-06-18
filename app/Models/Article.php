<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'reading_minutes' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Body split into paragraphs for rendering.
     *
     * @return array<int, string>
     */
    public function paragraphs(): array
    {
        return preg_split('/\n\s*\n/', trim($this->body)) ?: [];
    }
}
