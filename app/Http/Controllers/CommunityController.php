<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\CommunityGroup;
use App\Models\Post;
use App\Models\User;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CommunityController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        $followedIds = $user
            ? $user->followedGroups()->pluck('community_groups.id')->flip()
            : collect();
        $likedIds = $user
            ? $user->likedPosts()->pluck('posts.id')->flip()
            : collect();
        $bookmarkedPostIds = $user
            ? Bookmark::where('user_id', $user->id)
                ->where('bookmarkable_type', (new Post)->getMorphClass())
                ->pluck('bookmarkable_id')->flip()
            : collect();

        $groups = CommunityGroup::orderByDesc('members')->get()->map(fn (CommunityGroup $g) => [
            'id' => $g->id,
            'name' => $g->name,
            'description' => $g->description,
            'members' => $g->members,
            'color_from' => $g->color_from,
            'color_to' => $g->color_to,
            'following' => $followedIds->has($g->id),
            'is_owner' => $user && $g->user_id === $user->id,
        ]);

        $posts = Post::with(['group', 'comments' => fn ($q) => $q->oldest()])
            ->withCount('likers')
            ->latest()
            ->get()
            ->map(fn (Post $p) => [
                'id' => $p->id,
                'author_id' => $p->user_id,
                'author_name' => $p->author_name,
                'initial' => mb_substr($p->author_name, 0, 1),
                'avatar_color' => $p->avatar_color,
                'tag' => $p->tag,
                'body' => $p->body,
                'meta' => ($p->group?->name ?? 'Beheerder').' · '.$p->created_at->diffForHumans(),
                'like_count' => $p->base_likes + $p->likers_count,
                'liked' => $likedIds->has($p->id),
                'bookmarked' => $bookmarkedPostIds->has($p->id),
                'comment_count' => $p->comments->count(),
                'comments' => $p->comments->map(fn ($c) => [
                    'id' => $c->id,
                    'author_id' => $c->user_id,
                    'author_name' => $c->author_name,
                    'initial' => mb_substr($c->author_name, 0, 1),
                    'avatar_color' => $c->avatar_color,
                    'body' => $c->body,
                    'meta' => $c->created_at->diffForHumans(),
                ])->values(),
            ]);

        return Inertia::render('Community', [
            'profile' => $this->profileCard($user),
            'groups' => $groups,
            'posts' => $posts,
        ]);
    }

    /**
     * Build the sidebar profile card: the logged-in user's own profile,
     * or the founder card for guests.
     */
    private function profileCard(?User $user): array
    {
        if ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'type' => $user->parent_type_label,
                'role' => $user->role_label ?: 'Lid van de community 💛',
                'bio' => $user->bio ?: 'Welkom! Vul je bio aan op je profielpagina om jezelf voor te stellen.',
                'avatar_color' => $user->avatar_color ?: '#F7A8B5',
                'avatar_url' => $user->avatar_url,
                'stats' => [
                    ['value' => $user->followedGroups()->count(), 'label' => 'groepen'],
                    ['value' => $user->posts()->count(), 'label' => 'berichten'],
                    ['value' => $user->favouriteNames()->count(), 'label' => 'favorieten'],
                ],
                'editable' => true,
            ];
        }

        $founder = User::where('email', 'stephanie@mommylovesme.nl')->first();

        return [
            'name' => $founder?->name ?? 'Stephanie van der Kooij',
            'type' => 'Ouder',
            'role' => 'Oprichter · moeder van 4 💛',
            'bio' => 'Mama van vier (12, 9, 6 & 4). Mijn jongste heeft het Caprin1-syndroom - haar reis startte deze plek.',
            'avatar_color' => '#F7A8B5',
            'avatar_url' => $founder?->avatar_url,
            'stats' => [
                ['value' => 128, 'label' => 'vrienden'],
                ['value' => CommunityGroup::count(), 'label' => 'groepen'],
                ['value' => 340, 'label' => 'berichten'],
            ],
            'editable' => false,
        ];
    }

    public function storePost(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'community_group_id' => ['nullable', 'exists:community_groups,id'],
        ]);

        $user = $request->user();

        $user->posts()->create([
            'community_group_id' => $data['community_group_id'] ?? null,
            'author_name' => $user->name,
            'avatar_color' => $user->avatar_color ?: '#F7A8B5',
            'body' => $data['body'],
        ]);

        return back();
    }

    public function storeComment(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        $post->comments()->create([
            'user_id' => $user->id,
            'author_name' => $user->name,
            'avatar_color' => $user->avatar_color ?: '#CFE3F5',
            'body' => $data['body'],
        ]);

        // Notify the post author about the new comment.
        if ($post->user_id) {
            Notifier::send(
                recipient: $post->user_id,
                type: 'comment',
                title: $user->name.' reageerde op je bericht',
                body: \Illuminate\Support\Str::limit($data['body'], 60),
                url: route('community'),
                icon: '💬',
                actor: $user,
            );
        }

        return back();
    }

    public function toggleLike(Post $post): RedirectResponse
    {
        Auth::user()->likedPosts()->toggle($post->id);

        return back();
    }

    public function toggleFollow(CommunityGroup $group): RedirectResponse
    {
        $changes = Auth::user()->followedGroups()->toggle($group->id);

        // Keep the displayed member counter in step with follows.
        if (! empty($changes['attached'])) {
            $group->increment('members');
        } elseif (! empty($changes['detached'])) {
            $group->decrement('members');
            if ($group->members < 0) {
                $group->update(['members' => 0]);
            }
        }

        return back();
    }

    public function createGroup(): Response
    {
        return Inertia::render('Community/CreateGroup');
    }

    public function storeGroup(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
            'color_from' => ['nullable', 'string', 'regex:/^#([0-9A-Fa-f]{6})$/'],
            'color_to' => ['nullable', 'string', 'regex:/^#([0-9A-Fa-f]{6})$/'],
        ]);

        $user = $request->user();

        $group = CommunityGroup::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'members' => 1,
            'color_from' => $data['color_from'] ?: '#FCE7EB',
            'color_to' => $data['color_to'] ?: '#EAF5EE',
        ]);

        // The creator automatically follows their own group.
        $user->followedGroups()->attach($group->id);

        return redirect()->route('community')->with('success', 'Je groep is aangemaakt! 💛');
    }

    public function destroyGroup(Request $request, CommunityGroup $group): RedirectResponse
    {
        $user = $request->user();
        abort_unless($group->user_id === $user->id || $user->is_admin, 403);

        $group->delete();

        return redirect()->route('community')->with('success', 'Groep verwijderd.');
    }
}
