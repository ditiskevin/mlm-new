<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    protected $guarded = [];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    /**
     * Short type keys accepted from the client, mapped to model classes.
     * Keeps the public API stable and the input strictly validated.
     *
     * @var array<string, class-string<Model>>
     */
    public const TYPES = [
        'post' => Post::class,
        'comment' => Comment::class,
        'forum_topic' => ForumTopic::class,
        'forum_reply' => ForumReply::class,
        'listing' => Listing::class,
        'babysitter' => Babysitter::class,
        'article' => Article::class,
        'user' => User::class,
    ];

    /** Selectable report reasons. */
    public const REASONS = [
        'spam' => 'Spam of reclame',
        'ongepast' => 'Ongepaste inhoud',
        'intimidatie' => 'Pesten of intimidatie',
        'oplichting' => 'Nep of oplichting',
        'anders' => 'Iets anders',
    ];

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
