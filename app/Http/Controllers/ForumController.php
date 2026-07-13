<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Support\Mentions;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ForumController extends Controller
{
    public function index(): Response
    {
        $categories = ForumCategory::orderBy('position')
            ->withCount(['topics'])
            ->get()
            ->map(fn (ForumCategory $c) => [
                'name' => $c->name,
                'slug' => $c->slug,
                'description' => $c->description,
                'emoji' => $c->emoji,
                'color_from' => $c->color_from,
                'color_to' => $c->color_to,
                'topics_count' => $c->topics_count,
            ]);

        $recent = ForumTopic::with('category')
            ->withCount('replies')
            ->orderByDesc('last_activity_at')
            ->take(6)
            ->get()
            ->map(fn (ForumTopic $t) => $this->topicCard($t));

        return Inertia::render('Forum/Index', [
            'categories' => $categories,
            'recent' => $recent,
        ]);
    }

    public function category(ForumCategory $category): Response
    {
        $topics = $category->topics()
            ->withCount('replies')
            ->orderByDesc('pinned')
            ->orderByDesc('last_activity_at')
            ->get()
            ->map(fn (ForumTopic $t) => $this->topicCard($t));

        return Inertia::render('Forum/Category', [
            'category' => [
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'emoji' => $category->emoji,
                'color_from' => $category->color_from,
                'color_to' => $category->color_to,
            ],
            'topics' => $topics,
        ]);
    }

    public function topic(ForumTopic $topic): Response
    {
        $topic->load('category');
        $authId = auth()->id();

        // Which of this topic's replies did the current user mark helpful?
        $helpedIds = $authId
            ? DB::table('reply_helpful_votes')
                ->join('forum_replies', 'forum_replies.id', '=', 'reply_helpful_votes.forum_reply_id')
                ->where('forum_replies.forum_topic_id', $topic->id)
                ->where('reply_helpful_votes.user_id', $authId)
                ->pluck('reply_helpful_votes.forum_reply_id')
                ->all()
            : [];

        $replies = $topic->replies()
            ->withCount('helpfulVotes')
            ->oldest()
            ->get()
            ->sortByDesc('is_best') // best answer first, oldest-first among the rest
            ->values()
            ->map(fn ($r) => [
                'id' => $r->id,
                'author_id' => $r->user_id,
                'author_name' => $r->author_name,
                'avatar_color' => $r->avatar_color,
                'initial' => Str::upper(Str::substr($r->author_name, 0, 1)),
                'body' => $r->body,
                'created' => $r->created_at->diffForHumans(),
                'can_delete' => $authId && $authId === $r->user_id,
                'is_best' => (bool) $r->is_best,
                'helpful_count' => $r->helpful_votes_count,
                'helped' => in_array($r->id, $helpedIds, true),
            ]);

        return Inertia::render('Forum/Topic', [
            'topic' => [
                'id' => $topic->id,
                'slug' => $topic->slug,
                'title' => $topic->title,
                'body' => $topic->body,
                'author_id' => $topic->user_id,
                'author_name' => $topic->author_name,
                'avatar_color' => $topic->avatar_color,
                'initial' => Str::upper(Str::substr($topic->author_name, 0, 1)),
                'created' => $topic->created_at->diffForHumans(),
                'pinned' => $topic->pinned,
                'category' => ['name' => $topic->category->name, 'slug' => $topic->category->slug, 'emoji' => $topic->category->emoji],
                'can_delete' => $authId && $authId === $topic->user_id,
                'can_mark_best' => $authId && $authId === $topic->user_id,
            ],
            'replies' => $replies,
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Forum/CreateTopic', [
            'categories' => ForumCategory::orderBy('position')->get(['slug', 'name']),
            'preselect' => $request->query('category'),
        ]);
    }

    public function storeTopic(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'forum_category' => ['required', 'exists:forum_categories,slug'],
            'title' => ['required', 'string', 'max:140'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $user = $request->user();
        $category = ForumCategory::where('slug', $data['forum_category'])->firstOrFail();

        $topic = $category->topics()->create([
            'user_id' => $user->id,
            'author_name' => $user->name,
            'avatar_color' => $user->avatar_color ?: '#F7A8B5',
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title']),
            'body' => $data['body'],
            'last_activity_at' => now(),
        ]);

        return redirect()->route('forum.topic', $topic);
    }

    public function storeReply(Request $request, ForumTopic $topic): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $user = $request->user();

        $topic->replies()->create([
            'user_id' => $user->id,
            'author_name' => $user->name,
            'avatar_color' => $user->avatar_color ?: '#CFE3F5',
            'body' => $data['body'],
        ]);

        $topic->update(['last_activity_at' => now()]);

        // Notify the topic author about the new reply.
        if ($topic->user_id) {
            Notifier::send(
                recipient: $topic->user_id,
                type: 'forum_reply',
                title: $user->name.' reageerde op je onderwerp',
                body: Str::limit($data['body'], 60),
                url: route('forum.topic', $topic->slug),
                icon: '🗣️',
                actor: $user,
            );
        }

        Mentions::notify($data['body'], $user, route('forum.topic', $topic->slug), 'een forumreactie');

        return back();
    }

    public function destroyTopic(ForumTopic $topic): RedirectResponse
    {
        abort_unless(auth()->id() === $topic->user_id, 403);
        $slug = $topic->category->slug;
        $topic->delete();

        return redirect()->route('forum.category', $slug);
    }

    private function topicCard(ForumTopic $t): array
    {
        return [
            'slug' => $t->slug,
            'title' => $t->title,
            'author_name' => $t->author_name,
            'avatar_color' => $t->avatar_color,
            'initial' => Str::upper(Str::substr($t->author_name, 0, 1)),
            'replies_count' => $t->replies_count ?? 0,
            'pinned' => $t->pinned,
            'last_activity' => optional($t->last_activity_at)->diffForHumans() ?? $t->created_at->diffForHumans(),
            'category' => $t->relationLoaded('category') && $t->category ? ['name' => $t->category->name, 'slug' => $t->category->slug, 'emoji' => $t->category->emoji] : null,
        ];
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'onderwerp';
        $slug = $base;
        $i = 1;
        while (ForumTopic::where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
