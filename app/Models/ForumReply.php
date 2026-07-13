<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumReply extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_best' => 'boolean',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ForumTopic::class, 'forum_topic_id');
    }

    public function helpfulVotes(): HasMany
    {
        return $this->hasMany(ReplyHelpfulVote::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
