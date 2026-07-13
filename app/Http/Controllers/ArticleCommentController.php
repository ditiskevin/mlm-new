<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Support\Mentions;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleCommentController extends Controller
{
    public function store(Request $request, Article $article): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();

        ArticleComment::create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'avatar_color' => $user->avatar_color ?: '#CFE3F5',
            'body' => $data['body'],
        ]);

        // Notify the article author when someone else reacts to their story.
        if ($article->user_id && $article->user_id !== $user->id) {
            Notifier::send(
                recipient: $article->user_id,
                type: 'blog_comment',
                title: "{$user->name} reageerde op je verhaal",
                body: Str::limit($data['body'], 120),
                url: route('blog.show', $article->slug),
                icon: '💬',
                actor: $user,
            );
        }

        Mentions::notify($data['body'], $user, route('blog.show', $article->slug), 'een reactie');

        return back();
    }

    public function destroy(Request $request, ArticleComment $comment): RedirectResponse
    {
        $user = $request->user();
        abort_unless($comment->user_id === $user->id || $user->is_admin, 403);

        $comment->delete();

        return back();
    }
}
