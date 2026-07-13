<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class BookmarkController extends Controller
{
    /**
     * Toggle a bookmark for the authenticated user on the given item.
     * Adds the row if it is missing, removes it if it already exists.
     */
    public function toggle(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(Bookmark::TYPES))],
            'id' => ['required'],
        ]);

        $modelClass = Bookmark::TYPES[$data['type']];
        $bookmarkable = $modelClass::query()->findOrFail($data['id']);

        $user = $request->user();

        $existing = Bookmark::where('user_id', $user->id)
            ->where('bookmarkable_type', $bookmarkable->getMorphClass())
            ->where('bookmarkable_id', $bookmarkable->getKey())
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            $bookmark = new Bookmark(['user_id' => $user->id]);
            $bookmark->bookmarkable()->associate($bookmarkable);
            $bookmark->save();
        }

        return back();
    }

    /**
     * List the authenticated user's saved posts and articles, newest first.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $bookmarks = Bookmark::where('user_id', $user->id)
            ->with('bookmarkable')
            ->latest()
            ->get();

        $posts = $bookmarks
            ->where('bookmarkable_type', (new Post)->getMorphClass())
            ->map(fn (Bookmark $b) => $b->bookmarkable)
            ->filter()
            ->map(fn (Post $p) => [
                'id' => $p->id,
                'author_name' => $p->author_name,
                'excerpt' => Str::limit($p->body, 160),
                'when' => $p->created_at?->diffForHumans(),
            ])
            ->values();

        $articles = $bookmarks
            ->where('bookmarkable_type', (new Article)->getMorphClass())
            ->map(fn (Bookmark $b) => $b->bookmarkable)
            ->filter()
            ->map(fn (Article $a) => [
                'id' => $a->id,
                'title' => $a->title,
                'slug' => $a->slug,
                'category' => $a->category,
                'emoji' => $a->emoji,
                'excerpt' => $a->excerpt,
            ])
            ->values();

        return Inertia::render('Bookmarks/Index', [
            'posts' => $posts,
            'articles' => $articles,
        ]);
    }
}
