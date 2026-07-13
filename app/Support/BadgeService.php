<?php

namespace App\Support;

use App\Models\Article;
use App\Models\Badge;
use App\Models\User;

/**
 * Awards the gentle, supportive milestone badges. Badges are celebratory,
 * never competitive — every helper here is idempotent and safe to call from
 * request flows (a missing seed row is always a no-op).
 */
class BadgeService
{
    /**
     * Grant a badge to a member by its key.
     *
     * Returns true only when the badge was *newly* attached (so callers can
     * decide whether to celebrate). Sends the member an in-app notification
     * on a fresh award. A missing badge key is a silent no-op (returns false).
     */
    public static function award(User $user, string $key): bool
    {
        $badge = Badge::where('key', $key)->first();

        if (! $badge) {
            return false;
        }

        // Only insert the pivot when it is not already present (idempotent).
        $existing = $badge->users()->where('users.id', $user->id)->exists();

        if ($existing) {
            return false;
        }

        $badge->users()->attach($user->id);

        Notifier::send(
            $user,
            'badge',
            "Nieuwe badge: {$badge->name} {$badge->emoji}",
            $badge->description,
            route('members.show', $user->id),
            $badge->emoji,
        );

        return true;
    }

    /**
     * Evaluate the automatic, countable milestones for a member and award any
     * that are newly earned. Explicit badges ('welkom', 'behulpzaam') are
     * awarded by their own triggers, not here.
     *
     * Each check is guarded by a Badge existence lookup so a database that has
     * not seeded the badges is a harmless no-op.
     */
    public static function evaluate(User $user): void
    {
        if (Badge::where('key', 'eerste-bericht')->exists()
            && $user->posts()->count() >= 1) {
            self::award($user, 'eerste-bericht');
        }

        if (Badge::where('key', 'verhalenverteller')->exists()
            && Article::where('user_id', $user->id)->where('status', 'published')->count() >= 1) {
            self::award($user, 'verhalenverteller');
        }

        if (Badge::where('key', 'sociaal')->exists()
            && $user->followers()->count() >= 10) {
            self::award($user, 'sociaal');
        }
    }
}
