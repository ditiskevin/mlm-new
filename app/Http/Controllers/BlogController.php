<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $category = $request->query('category');

        $query = Article::query()->whereNotNull('published_at')->latest('published_at');
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

        $categories = Article::whereNotNull('published_at')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return Inertia::render('Blog/Index', [
            'articles' => $articles,
            'categories' => $categories,
            'activeCategory' => $category,
        ]);
    }

    public function show(Article $article): Response
    {
        $related = Article::whereNotNull('published_at')
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
                'title' => $article->title,
                'category' => $article->category,
                'emoji' => $article->emoji,
                'color_from' => $article->color_from,
                'color_to' => $article->color_to,
                'reading_minutes' => $article->reading_minutes,
                'author_name' => $article->author_name,
                'published' => $article->published_at?->translatedFormat('j F Y'),
                'paragraphs' => $article->paragraphs(),
            ],
            'related' => $related,
        ]);
    }
}
