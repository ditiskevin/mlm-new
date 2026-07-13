<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class FollowController extends Controller
{
    /**
     * Follow / unfollow another member.
     */
    public function toggle(Request $request, User $user): RedirectResponse
    {
        $me = $request->user();

        if ($user->id === $me->id) {
            return back()->with('error', 'Je kunt jezelf niet volgen.');
        }

        $existing = DB::table('user_follows')
            ->where('follower_id', $me->id)
            ->where('followed_id', $user->id)
            ->exists();

        if ($existing) {
            DB::table('user_follows')
                ->where('follower_id', $me->id)
                ->where('followed_id', $user->id)
                ->delete();
        } else {
            DB::table('user_follows')->insert([
                'follower_id' => $me->id,
                'followed_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Notifier::send(
                recipient: $user,
                type: 'follow',
                title: "{$me->name} volgt je nu",
                body: null,
                url: route('members.show', $me->id),
                icon: '👋',
                actor: $me,
            );
        }

        return back();
    }

    /**
     * Timeline of posts from the members the current user follows.
     */
    public function following(Request $request): Response
    {
        $me = $request->user();

        $followedIds = DB::table('user_follows')
            ->where('follower_id', $me->id)
            ->pluck('followed_id');

        $posts = Post::whereIn('user_id', $followedIds)
            ->latest()
            ->get()
            ->map(fn (Post $p) => [
                'id' => $p->id,
                'author_id' => $p->user_id,
                'author_name' => $p->author_name,
                'initial' => mb_substr($p->author_name, 0, 1),
                'avatar_color' => $p->avatar_color ?: '#F7A8B5',
                'body' => $p->body,
                'when' => $p->created_at?->diffForHumans(),
            ]);

        return Inertia::render('Feed/Following', [
            'posts' => $posts,
            'count' => $followedIds->count(),
        ]);
    }
}
