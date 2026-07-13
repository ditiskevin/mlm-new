<?php

namespace App\Support;

use App\Events\NotificationSent;
use App\Models\Notification;
use App\Models\User;

/**
 * Central helper for creating + broadcasting in-app notifications.
 * Every feature (messages, mentions, follows, reactions, badges, …) calls this.
 */
class Notifier
{
    /**
     * @param  User|int  $recipient
     */
    public static function send(
        User|int $recipient,
        string $type,
        string $title,
        ?string $body = null,
        ?string $url = null,
        string $icon = '🔔',
        ?User $actor = null,
    ): ?Notification {
        $recipientId = $recipient instanceof User ? $recipient->id : $recipient;

        // Never notify someone about their own action.
        if ($actor && $actor->id === $recipientId) {
            return null;
        }

        $notification = Notification::create([
            'user_id' => $recipientId,
            'actor_id' => $actor?->id,
            'type' => $type,
            'icon' => $icon,
            'title' => $title,
            'body' => $body,
            'url' => $url,
        ]);

        $unread = Notification::where('user_id', $recipientId)->whereNull('read_at')->count();

        try {
            NotificationSent::dispatch($notification, $unread);
        } catch (\Throwable $e) {
            // Broadcasting is best-effort; the notification is already stored.
        }

        return $notification;
    }
}
