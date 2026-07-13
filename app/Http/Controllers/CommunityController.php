<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\CommunityGroup;
use App\Models\Poll;
use App\Models\PollVote;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use App\Support\BadgeService;
use App\Support\Mentions;
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
        $hiddenIds = $user ? $user->blockedIdsWith() : [];
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

        $postModels = Post::with(['group', 'comments' => fn ($q) => $q->oldest()])
            ->withCount('likers')
            ->when($hiddenIds, fn ($q) => $q->whereNotIn('user_id', $hiddenIds))
            ->latest()
            ->get();

        // --- Emoji reactions (grouped, no N+1) ---
        $postMorph = (new Post)->getMorphClass();
        $commentMorph = (new Comment)->getMorphClass();
        $postIds = $postModels->pluck('id');
        $commentIds = $postModels->flatMap->comments->pluck('id');

        $summary = fn (string $morph, $ids) => $ids->isEmpty() ? collect() : Reaction::selectRaw('reactable_id, emoji, COUNT(*) as count')
            ->where('reactable_type', $morph)
            ->whereIn('reactable_id', $ids)
            ->groupBy('reactable_id', 'emoji')
            ->get()
            ->groupBy('reactable_id')
            ->map(fn ($rows) => $rows->map(fn ($r) => ['emoji' => $r->emoji, 'count' => (int) $r->count])->values());

        $postReactions = $summary($postMorph, $postIds);
        $commentReactions = $summary($commentMorph, $commentIds);

        $myReactions = $user
            ? Reaction::where('user_id', $user->id)
                ->where(function ($q) use ($postMorph, $postIds, $commentMorph, $commentIds) {
                    $q->where(fn ($qq) => $qq->where('reactable_type', $postMorph)->whereIn('reactable_id', $postIds))
                        ->orWhere(fn ($qq) => $qq->where('reactable_type', $commentMorph)->whereIn('reactable_id', $commentIds));
                })
                ->get()
                ->groupBy('reactable_type')
                ->map(fn ($rows) => $rows->keyBy('reactable_id')->map->emoji)
            : collect();

        $posts = $postModels->map(fn (Post $p) => [
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
            'reactions' => $postReactions->get($p->id, collect())->values(),
            'my_reaction' => optional($myReactions->get($postMorph))->get($p->id),
            'comment_count' => $p->comments->reject(fn ($c) => in_array($c->user_id, $hiddenIds, true))->count(),
            'comments' => $p->comments->reject(fn ($c) => in_array($c->user_id, $hiddenIds, true))->map(fn ($c) => [
                'id' => $c->id,
                'author_id' => $c->user_id,
                'author_name' => $c->author_name,
                'initial' => mb_substr($c->author_name, 0, 1),
                'avatar_color' => $c->avatar_color,
                'body' => $c->body,
                'meta' => $c->created_at->diffForHumans(),
                'reactions' => $commentReactions->get($c->id, collect())->values(),
                'my_reaction' => optional($myReactions->get($commentMorph))->get($c->id),
            ])->values(),
        ]);

        // --- Polls ---
        $myPollVotes = $user
            ? PollVote::where('user_id', $user->id)->pluck('poll_option_id', 'poll_id')
            : collect();

        $polls = Poll::with(['options', 'group'])
            ->withCount('votes')
            ->latest()
            ->get()
            ->map(function (Poll $poll) use ($user, $myPollVotes) {
                $counts = PollVote::where('poll_id', $poll->id)
                    ->selectRaw('poll_option_id, COUNT(*) as aggregate')
                    ->groupBy('poll_option_id')
                    ->pluck('aggregate', 'poll_option_id');
                $total = (int) $poll->votes_count;

                return [
                    'id' => $poll->id,
                    'question' => $poll->question,
                    'author_name' => $poll->author_name,
                    'initial' => mb_substr($poll->author_name, 0, 1),
                    'avatar_color' => $poll->avatar_color,
                    'when' => ($poll->group?->name ?? 'Community').' · '.$poll->created_at->diffForHumans(),
                    'options' => $poll->options->map(fn ($opt) => [
                        'id' => $opt->id,
                        'label' => $opt->label,
                        'votes' => (int) ($counts[$opt->id] ?? 0),
                        'percent' => $total > 0 ? (int) round(($counts[$opt->id] ?? 0) / $total * 100) : 0,
                    ])->values(),
                    'total_votes' => $total,
                    'my_option' => $myPollVotes[$poll->id] ?? null,
                    'can_delete' => $user && ($poll->user_id === $user->id || $user->is_admin),
                ];
            });

        return Inertia::render('Community', [
            'profile' => $this->profileCard($user),
            'groups' => $groups,
            'posts' => $posts,
            'polls' => $polls,
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

        BadgeService::evaluate($user);
        Mentions::notify($data['body'], $user, route('community'), 'een bericht');

        return back();
    }

    public function showGroup(Request $request, CommunityGroup $group): Response
    {
        $user = $request->user();
        $following = $user ? $user->followedGroups()->whereKey($group->id)->exists() : false;

        $posts = Post::where('community_group_id', $group->id)
            ->withCount('likers')
            ->latest()
            ->take(30)
            ->get()
            ->map(fn (Post $p) => [
                'id' => $p->id,
                'author_id' => $p->user_id,
                'author_name' => $p->author_name,
                'initial' => mb_substr($p->author_name, 0, 1),
                'avatar_color' => $p->avatar_color,
                'body' => $p->body,
                'when' => $p->created_at->diffForHumans(),
                'like_count' => $p->base_likes + $p->likers_count,
            ]);

        $members = $group->followers()->latest('community_group_user.created_at')->take(24)->get()->map(fn (User $u) => [
            'id' => $u->id,
            'name' => $u->name,
            'initial' => mb_substr($u->name, 0, 1),
            'avatar_color' => $u->avatar_color ?: '#F7A8B5',
            'avatar_url' => $u->avatar_url,
        ]);

        return Inertia::render('Community/Group', [
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
                'description' => $group->description,
                'members' => $group->members,
                'color_from' => $group->color_from,
                'color_to' => $group->color_to,
                'following' => $following,
                'is_owner' => $user && $group->user_id === $user->id,
                'owner' => $group->user?->name,
            ],
            'posts' => $posts,
            'members_preview' => $members,
        ]);
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

        Mentions::notify($data['body'], $user, route('community'), 'een reactie');

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
