<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
{
    /**
     * Public profile of a community member.
     */
    public function show(User $user): Response
    {
        $viewer = auth()->user();
        $isFollowing = $viewer && $viewer->id !== $user->id ? $viewer->isFollowing($user) : false;

        $posts = Post::where('user_id', $user->id)
            ->with('group:id,name')
            ->withCount('likers')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (Post $p) => [
                'id' => $p->id,
                'body' => $p->body,
                'tag' => $p->tag,
                'group' => $p->group?->name,
                'like_count' => $p->base_likes + $p->likers_count,
                'when' => $p->created_at->diffForHumans(),
            ]);

        return Inertia::render('Members/Show', [
            'member' => [
                'id' => $user->id,
                'name' => $user->name,
                'initial' => mb_substr($user->name, 0, 1),
                'avatar_color' => $user->avatar_color ?: '#F7A8B5',
                'avatar_url' => $user->avatar_url,
                'parent_type' => $user->parent_type_label,
                'gender' => $user->gender_label,
                'parenting_role' => $user->parenting_role_label,
                'role' => $user->role_label,
                'bio' => $user->bio,
                'member_since' => $user->created_at?->translatedFormat('F Y'),
                'is_admin' => (bool) $user->is_admin,
                'stats' => [
                    ['value' => $user->posts()->count(), 'label' => 'berichten'],
                    ['value' => $user->followers()->count(), 'label' => 'volgers'],
                    ['value' => $user->followedGroups()->count(), 'label' => 'groepen'],
                    ['value' => $user->favouriteNames()->count(), 'label' => 'favorieten'],
                ],
            ],
            'posts' => $posts,
            'isSelf' => $viewer && $viewer->id === $user->id,
            'canMessage' => $viewer && $viewer->id !== $user->id,
            'isFollowing' => $isFollowing,
        ]);
    }
}
