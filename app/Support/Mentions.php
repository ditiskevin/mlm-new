<?php

namespace App\Support;

use App\Models\User;

/**
 * Parses @username mentions from user-written text and notifies the members
 * that were mentioned. Used by community posts/comments, forum replies and
 * blog comments.
 */
class Mentions
{
    /**
     * @return array<int, int> the ids of the users that were notified
     */
    public static function notify(string $body, User $actor, string $url, string $context = 'een bericht'): array
    {
        preg_match_all('/@([a-z0-9_]{2,30})/i', $body, $matches);

        if (empty($matches[1])) {
            return [];
        }

        $handles = collect($matches[1])->map(fn ($h) => strtolower($h))->unique();

        $mentioned = User::whereIn('username', $handles)
            ->where('id', '!=', $actor->id)
            ->get();

        foreach ($mentioned as $user) {
            Notifier::send(
                recipient: $user,
                type: 'mention',
                title: $actor->name.' noemde je in '.$context,
                body: \Illuminate\Support\Str::limit($body, 80),
                url: $url,
                icon: '📣',
                actor: $actor,
            );
        }

        return $mentioned->pluck('id')->all();
    }
}
