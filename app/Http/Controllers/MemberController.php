<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
{
    /**
     * Browsable directory of community members.
     */
    public function index(Request $request): Response
    {
        $viewer = $request->user();
        $q = trim((string) $request->query('q'));
        $followingIds = $viewer
            ? \Illuminate\Support\Facades\DB::table('user_follows')->where('follower_id', $viewer->id)->pluck('followed_id')->flip()
            : collect();

        $members = User::query()
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('username', 'like', "%{$q}%")))
            ->orderByDesc('id')
            ->limit(120)
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'username' => $u->username,
                'initial' => mb_substr($u->name, 0, 1),
                'avatar_color' => $u->avatar_color ?: '#F7A8B5',
                'avatar_url' => $u->avatar_url,
                'role' => $u->parenting_role_label ?: $u->parent_type_label,
                'is_self' => $viewer && $viewer->id === $u->id,
                'following' => $followingIds->has($u->id),
            ]);

        return Inertia::render('Members/Index', [
            'members' => $members,
            'filters' => ['q' => $q],
            'total' => User::count(),
        ]);
    }

    /**
     * Public profile of a community member.
     */
    public function show(User $user): Response
    {
        $viewer = auth()->user();
        $isFollowing = $viewer && $viewer->id !== $user->id ? $viewer->isFollowing($user) : false;

        $mapMini = fn (User $u) => [
            'id' => $u->id,
            'name' => $u->name,
            'initial' => mb_substr($u->name, 0, 1),
            'avatar_color' => $u->avatar_color ?: '#F7A8B5',
            'avatar_url' => $u->avatar_url,
        ];

        $followerIds = $user->followers()->pluck('users.id');
        $followingIds = $user->following()->pluck('users.id');
        $friendIds = $followerIds->intersect($followingIds); // mutual = friends
        $friends = User::whereIn('id', $friendIds)->take(12)->get()->map($mapMini);

        // Does the viewer already follow this member back (are they friends)?
        $areFriends = $viewer && $isFollowing && $user->isFollowing($viewer);

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
                'username' => $user->username,
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
            'isBlocked' => $viewer && $viewer->id !== $user->id ? $viewer->hasBlocked($user) : false,
            'areFriends' => (bool) $areFriends,
            'connections' => [
                'followers' => $followerIds->count(),
                'following' => $followingIds->count(),
                'friends' => $friendIds->count(),
                'friendsPreview' => $friends,
            ],
            'badges' => $user->badges()->orderBy('name')->get()->map(fn (\App\Models\Badge $b) => [
                'key' => $b->key,
                'name' => $b->name,
                'emoji' => $b->emoji,
                'description' => $b->description,
            ]),
        ]);
    }

    /**
     * Full followers / following list for a member.
     */
    public function connections(Request $request, User $user, string $type): Response
    {
        abort_unless(in_array($type, ['volgers', 'volgend'], true), 404);

        $relation = $type === 'volgers' ? $user->followers() : $user->following();
        $people = $relation->orderBy('name')->get()->map(fn (User $u) => [
            'id' => $u->id,
            'name' => $u->name,
            'username' => $u->username,
            'initial' => mb_substr($u->name, 0, 1),
            'avatar_color' => $u->avatar_color ?: '#F7A8B5',
            'avatar_url' => $u->avatar_url,
            'role' => $u->parenting_role_label ?: $u->parent_type_label,
        ]);

        return Inertia::render('Members/Connections', [
            'member' => ['id' => $user->id, 'name' => $user->name],
            'type' => $type,
            'people' => $people,
        ]);
    }
}
