<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    protected $guarded = [];

    /**
     * Short type keys accepted from the client, mapped to model classes.
     * Keeps the public API stable and the input strictly validated.
     *
     * @var array<string, class-string<Model>>
     */
    public const TYPES = [
        'post' => Post::class,
        'comment' => Comment::class,
    ];

    /** The care-reactions members can leave. */
    public const EMOJIS = ['❤️', '💛', '🤗', '👏', '😢'];

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
