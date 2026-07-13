<?php

namespace App\Http\Controllers;

use App\Models\ForumReply;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForumInteractionController extends Controller
{
    /**
     * Mark (or un-mark) a reply as the topic's best answer.
     * Only the topic author may do this.
     */
    public function markBest(Request $request, ForumReply $reply): RedirectResponse
    {
        $topic = $reply->topic;

        abort_unless($request->user() && $request->user()->id === $topic->user_id, 403);

        $wasBest = (bool) $reply->is_best;

        // Only one best answer per topic: clear the others first.
        ForumReply::where('forum_topic_id', $topic->id)
            ->where('id', '!=', $reply->id)
            ->update(['is_best' => false]);

        // Toggle this reply.
        $reply->update(['is_best' => ! $wasBest]);

        // Notify the reply's author when newly marked best (not when un-marking).
        if (! $wasBest && $reply->user_id && $reply->user_id !== $request->user()->id) {
            Notifier::send(
                $reply->user_id,
                'best_answer',
                'Je antwoord is gemarkeerd als beste antwoord 🏅',
                null,
                route('forum.topic', $topic->slug),
                '🏅',
                $request->user(),
            );
        }

        return back();
    }

    /**
     * Toggle a "this helped me" vote for the current user on a reply.
     */
    public function toggleHelpful(Request $request, ForumReply $reply): RedirectResponse
    {
        $userId = $request->user()->id;

        $existing = DB::table('reply_helpful_votes')
            ->where('forum_reply_id', $reply->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            DB::table('reply_helpful_votes')
                ->where('id', $existing->id)
                ->delete();

            return back();
        }

        DB::table('reply_helpful_votes')->insert([
            'forum_reply_id' => $reply->id,
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Notify the reply's author on a new helpful vote.
        if ($reply->user_id && $reply->user_id !== $userId) {
            Notifier::send(
                $reply->user_id,
                'helpful',
                'Iemand vond je antwoord behulpzaam 💛',
                null,
                route('forum.topic', $reply->topic->slug),
                '💛',
                $request->user(),
            );
        }

        return back();
    }
}
