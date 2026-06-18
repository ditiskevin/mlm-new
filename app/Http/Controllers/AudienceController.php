<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Audience;
use Inertia\Inertia;
use Inertia\Response;

class AudienceController extends Controller
{
    public function index(): Response
    {
        $audiences = Audience::orderBy('position')->get()->map(fn (Audience $a) => [
            'name' => $a->name,
            'slug' => $a->slug,
            'emoji' => $a->emoji,
            'tagline' => $a->tagline,
            'intro' => $a->intro,
            'color_from' => $a->color_from,
            'color_to' => $a->color_to,
        ]);

        return Inertia::render('Audiences/Index', [
            'audiences' => $audiences,
        ]);
    }

    public function show(Audience $audience): Response
    {
        $audience->load(['group', 'forumCategory']);

        $forumCategory = $audience->forumCategory;
        $forumCategory?->loadCount('topics');

        $articles = Article::where('audience_id', $audience->id)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->get()
            ->map(fn (Article $a) => [
                'title' => $a->title,
                'slug' => $a->slug,
                'emoji' => $a->emoji,
                'color_from' => $a->color_from,
                'color_to' => $a->color_to,
                'reading_minutes' => $a->reading_minutes,
                'excerpt' => $a->excerpt,
            ]);

        return Inertia::render('Audiences/Show', [
            'audience' => [
                'name' => $audience->name,
                'slug' => $audience->slug,
                'emoji' => $audience->emoji,
                'tagline' => $audience->tagline,
                'color_from' => $audience->color_from,
                'color_to' => $audience->color_to,
                'paragraphs' => $audience->paragraphs(),
                'tips' => $audience->tips ?? [],
            ],
            'group' => $audience->group ? [
                'name' => $audience->group->name,
                'color_from' => $audience->group->color_from,
                'color_to' => $audience->group->color_to,
            ] : null,
            'forumCategory' => $forumCategory ? [
                'name' => $forumCategory->name,
                'slug' => $forumCategory->slug,
                'emoji' => $forumCategory->emoji,
                'topics_count' => $forumCategory->topics_count,
            ] : null,
            'articles' => $articles,
        ]);
    }
}
