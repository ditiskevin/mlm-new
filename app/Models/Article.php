<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Articles that are live on the site. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
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
