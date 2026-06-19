<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    /**
     * Find an existing 1-to-1 conversation between two users, or create one.
     */
    public static function between(User $a, User $b): self
    {
        $conversation = self::query()
            ->whereHas('participants', fn ($q) => $q->whereKey($a->id))
            ->whereHas('participants', fn ($q) => $q->whereKey($b->id))
            ->withCount('participants')
            ->get()
            ->firstWhere('participants_count', 2);

        if ($conversation) {
            return $conversation;
        }

        $conversation = self::create();
        $conversation->participants()->attach([$a->id, $b->id]);

        return $conversation;
    }
}
