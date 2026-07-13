<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\Bookmark;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $category = $request->query('category');

        $query = Article::published()->latest('published_at');
        if ($category) {
            $query->where('category', $category);
        }

        $articles = $query->get()->map(fn (Article $a) => [
            'title' => $a->title,
            'slug' => $a->slug,
            'category' => $a->category,
            'emoji' => $a->emoji,
            'color_from' => $a->color_from,
            'color_to' => $a->color_to,
            'reading_minutes' => $a->reading_minutes,
            'excerpt' => $a->excerpt,
            'author_name' => $a->author_name,
            'published' => $a->published_at?->translatedFormat('j M Y'),
        ]);

        $categories = Article::published()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $user = $request->user();
        $mySubmissions = $user
            ? Article::where('user_id', $user->id)
                ->where('status', '!=', 'published')
                ->latest()
                ->get()
                ->map(fn (Article $a) => [
                    'title' => $a->title,
                    'slug' => $a->slug,
                    'status' => $a->status,
                ])
            : [];

        return Inertia::render('Blog/Index', [
            'articles' => $articles,
            'categories' => $categories,
            'activeCategory' => $category,
            'canWrite' => (bool) $user,
            'mySubmissions' => $mySubmissions,
        ]);
    }

    public function show(Request $request, Article $article): Response
    {
        $user = $request->user();

        // Pending/rejected articles are visible only to their author or an admin.
        if ($article->status !== 'published' || ! $article->published_at) {
            abort_unless($user && ($user->is_admin || $user->id === $article->user_id), 404);
        }

        $bookmarked = $user
            ? Bookmark::where('user_id', $user->id)
                ->where('bookmarkable_type', $article->getMorphClass())
                ->where('bookmarkable_id', $article->id)
                ->exists()
            : false;

        $comments = ArticleComment::where('article_id', $article->id)
            ->oldest()
            ->get()
            ->map(fn (ArticleComment $c) => [
                'id' => $c->id,
                'author_name' => $c->author_name,
                'initial' => mb_substr($c->author_name, 0, 1),
                'avatar_color' => $c->avatar_color,
                'body' => $c->body,
                'when' => $c->created_at->diffForHumans(),
                'can_delete' => $user && ($user->id === $c->user_id || $user->is_admin),
            ]);

        $related = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get()
            ->map(fn (Article $a) => [
                'title' => $a->title,
                'slug' => $a->slug,
                'category' => $a->category,
                'emoji' => $a->emoji,
                'color_from' => $a->color_from,
                'color_to' => $a->color_to,
                'reading_minutes' => $a->reading_minutes,
                'excerpt' => $a->excerpt,
            ]);

        return Inertia::render('Blog/Show', [
            'article' => [
                'id' => $article->id,
                'slug' => $article->slug,
                'title' => $article->title,
                'category' => $article->category,
                'emoji' => $article->emoji,
                'color_from' => $article->color_from,
                'color_to' => $article->color_to,
                'reading_minutes' => $article->reading_minutes,
                'author_name' => $article->author_name,
                'status' => $article->status,
                'published' => $article->published_at?->translatedFormat('j F Y'),
                'paragraphs' => $article->paragraphs(),
                'bookmarked' => $bookmarked,
            ],
            'related' => $related,
            'comments' => $comments,
            'canComment' => (bool) $user,
        ]);
    }

    /** Story-writing form for logged-in members. */
    public function create(Request $request): Response
    {
        return Inertia::render('Blog/Create', [
            'categories' => Article::published()->select('category')->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    /**
     * Store a member-submitted story. It stays "pending" until an admin approves it.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'category' => ['required', 'string', 'max:60'],
            'emoji' => ['nullable', 'string', 'max:8'],
            'excerpt' => ['required', 'string', 'min:20', 'max:300'],
            'body' => ['required', 'string', 'min:200', 'max:20000'],
        ]);

        $user = $request->user();

        // ~200 words per minute, at least 1.
        $words = str_word_count(strip_tags($data['body']));
        $minutes = max(1, (int) round($words / 200));

        Article::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title']),
            'category' => $data['category'],
            'author_name' => $user->name,
            'status' => 'pending',
            'emoji' => $data['emoji'] ?: '💛',
            'reading_minutes' => $minutes,
            'excerpt' => $data['excerpt'],
            'body' => $data['body'],
            'published_at' => null,
        ]);

        return redirect()->route('blog.index')
            ->with('success', 'Bedankt voor je verhaal! Een beheerder bekijkt het en plaatst het zo snel mogelijk. 💛');
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'verhaal';
        $slug = $base;
        $i = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
