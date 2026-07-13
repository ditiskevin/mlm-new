<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Bookmark extends Model
{
    protected $guarded = [];

    /**
     * Short type keys accepted from the client, mapped to model classes.
     * Mirrors the Report::TYPES allowlist pattern so input stays strictly validated.
     *
     * @var array<string, class-string<Model>>
     */
    public const TYPES = [
        'post' => Post::class,
        'article' => Article::class,
    ];

    public function bookmarkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
