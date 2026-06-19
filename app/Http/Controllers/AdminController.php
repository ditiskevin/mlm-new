<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Audience;
use App\Models\Babysitter;
use App\Models\Comment;
use App\Models\CommunityGroup;
use App\Models\ContactMessage;
use App\Models\ForumCategory;
use App\Models\ForumReply;
use App\Models\ForumTopic;
use App\Models\Listing;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'openContactCount' => ContactMessage::whereNull('handled_at')->count(),
            'stats' => [
                ['label' => 'Leden', 'value' => User::count(), 'emoji' => '👥'],
                ['label' => 'Berichten', 'value' => Post::count(), 'emoji' => '💬'],
                ['label' => 'Reacties', 'value' => Comment::count() + ForumReply::count(), 'emoji' => '💭'],
                ['label' => 'Forumonderwerpen', 'value' => ForumTopic::count(), 'emoji' => '🗣️'],
                ['label' => 'Advertenties', 'value' => Listing::count(), 'emoji' => '🛍️'],
                ['label' => 'Oppasprofielen', 'value' => Babysitter::count(), 'emoji' => '🧸'],
            ],
            'posts' => Post::latest()->take(20)->get()->map(fn (Post $p) => [
                'id' => $p->id,
                'author' => $p->author_name,
                'excerpt' => Str::limit($p->body, 100),
                'when' => $p->created_at->diffForHumans(),
            ]),
            'comments' => Comment::with('post')->latest()->take(20)->get()->map(fn (Comment $c) => [
                'id' => $c->id,
                'author' => $c->author_name,
                'excerpt' => Str::limit($c->body, 100),
                'when' => $c->created_at->diffForHumans(),
            ]),
            'topics' => ForumTopic::with('category')->latest()->take(20)->get()->map(fn (ForumTopic $t) => [
                'id' => $t->slug,
                'title' => $t->title,
                'author' => $t->author_name,
                'category' => $t->category?->name,
                'when' => $t->created_at->diffForHumans(),
            ]),
            'replies' => ForumReply::latest()->take(20)->get()->map(fn (ForumReply $r) => [
                'id' => $r->id,
                'author' => $r->author_name,
                'excerpt' => Str::limit($r->body, 100),
                'when' => $r->created_at->diffForHumans(),
            ]),
            'listings' => Listing::latest()->take(20)->get()->map(fn (Listing $l) => [
                'id' => $l->slug,
                'title' => $l->title,
                'author' => $l->author_name,
                'category' => $l->category,
                'when' => $l->created_at->diffForHumans(),
            ]),
            'babysitters' => Babysitter::latest()->take(20)->get()->map(fn (Babysitter $b) => [
                'id' => $b->id,
                'name' => $b->name,
                'location' => $b->location,
                'when' => $b->created_at->diffForHumans(),
            ]),
            'articles' => Article::latest()->take(20)->get()->map(fn (Article $a) => [
                'id' => $a->slug,
                'title' => $a->title,
                'category' => $a->category,
                'when' => optional($a->published_at)->diffForHumans(),
            ]),
            'users' => User::orderBy('name')->get()->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'is_admin' => $u->is_admin,
                'is_self' => $u->id === auth()->id(),
            ]),
        ]);
    }

    public function destroyPost(Post $post): RedirectResponse
    {
        $post->delete();

        return back();
    }

    public function destroyComment(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back();
    }

    public function destroyTopic(ForumTopic $topic): RedirectResponse
    {
        $topic->delete();

        return back();
    }

    public function destroyReply(ForumReply $reply): RedirectResponse
    {
        $reply->delete();

        return back();
    }

    public function destroyListing(Listing $listing): RedirectResponse
    {
        $listing->delete();

        return back();
    }

    public function destroyBabysitter(Babysitter $babysitter): RedirectResponse
    {
        $babysitter->delete();

        return back();
    }

    public function destroyArticle(Article $article): RedirectResponse
    {
        $article->delete();

        return back();
    }

    public function toggleAdmin(User $user): RedirectResponse
    {
        // Don't let an admin remove their own admin rights (avoid lock-out).
        if ($user->id !== auth()->id()) {
            $user->is_admin = ! $user->is_admin;
            $user->save();
        }

        return back();
    }

    // ---- Contactberichten ----

    public function contact(): Response
    {
        return Inertia::render('Admin/Contact/Index', [
            'messages' => ContactMessage::with('user:id,name')->latest()->get()->map(fn (ContactMessage $m) => [
                'id' => $m->id,
                'name' => $m->name,
                'email' => $m->email,
                'subject' => $m->subject,
                'message' => $m->message,
                'is_member' => (bool) $m->user_id,
                'handled' => (bool) $m->handled_at,
                'when' => $m->created_at->diffForHumans(),
            ]),
        ]);
    }

    public function toggleContactHandled(ContactMessage $message): RedirectResponse
    {
        $message->handled_at = $message->handled_at ? null : now();
        $message->save();

        return back();
    }

    public function destroyContact(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back();
    }

    // ---- Audiences (doelgroepen / "Ik ben…" opties) ----

    public function audiences(): Response
    {
        return Inertia::render('Admin/Audiences/Index', [
            'audiences' => Audience::orderBy('position')->withCount('articles')->get()->map(fn (Audience $a) => [
                'slug' => $a->slug,
                'name' => $a->name,
                'emoji' => $a->emoji,
                'tagline' => $a->tagline,
                'articles_count' => $a->articles_count,
            ]),
        ]);
    }

    public function createAudience(): Response
    {
        return Inertia::render('Admin/Audiences/Form', ['audience' => null]);
    }

    public function editAudience(Audience $audience): Response
    {
        return Inertia::render('Admin/Audiences/Form', [
            'audience' => [
                'slug' => $audience->slug,
                'name' => $audience->name,
                'emoji' => $audience->emoji,
                'tagline' => $audience->tagline,
                'intro' => $audience->intro,
                'body' => $audience->body,
                'tips' => implode("\n", $audience->tips ?? []),
                'color_from' => $audience->color_from,
                'color_to' => $audience->color_to,
            ],
        ]);
    }

    public function storeAudience(Request $request): RedirectResponse
    {
        $data = $this->validateAudience($request);
        $slug = $this->uniqueAudienceSlug($data['name']);

        $audience = Audience::create([
            'name' => $data['name'],
            'slug' => $slug,
            'emoji' => $data['emoji'] ?: '💛',
            'tagline' => $data['tagline'],
            'intro' => $data['intro'],
            'body' => $data['body'],
            'tips' => $this->splitTips($data['tips'] ?? ''),
            'color_from' => $data['color_from'] ?: '#FCE7EB',
            'color_to' => $data['color_to'] ?: '#EAF5EE',
            'position' => (int) Audience::max('position') + 1,
        ]);

        // Auto-create the linked community group + forum category so the hub works.
        CommunityGroup::firstOrCreate(['name' => $audience->name], [
            'audience_id' => $audience->id,
            'members' => 0,
            'color_from' => $audience->color_from,
            'color_to' => $audience->color_to,
        ]);

        ForumCategory::updateOrCreate(['slug' => $audience->slug], [
            'audience_id' => $audience->id,
            'name' => $audience->name,
            'description' => $audience->tagline.'.',
            'emoji' => $audience->emoji,
            'color_from' => $audience->color_from,
            'color_to' => $audience->color_to,
            'position' => 20 + (int) $audience->position,
        ]);

        return redirect()->route('admin.audiences.index');
    }

    public function updateAudience(Request $request, Audience $audience): RedirectResponse
    {
        $data = $this->validateAudience($request);

        $audience->update([
            'name' => $data['name'],
            'emoji' => $data['emoji'] ?: '💛',
            'tagline' => $data['tagline'],
            'intro' => $data['intro'],
            'body' => $data['body'],
            'tips' => $this->splitTips($data['tips'] ?? ''),
            'color_from' => $data['color_from'] ?: '#FCE7EB',
            'color_to' => $data['color_to'] ?: '#EAF5EE',
        ]);

        // Keep the linked group + forum category in sync.
        $audience->group?->update(['name' => $audience->name, 'color_from' => $audience->color_from, 'color_to' => $audience->color_to]);
        $audience->forumCategory?->update(['name' => $audience->name, 'description' => $audience->tagline.'.', 'emoji' => $audience->emoji, 'color_from' => $audience->color_from, 'color_to' => $audience->color_to]);

        return redirect()->route('admin.audiences.index');
    }

    public function destroyAudience(Audience $audience): RedirectResponse
    {
        // FKs use nullOnDelete, so the (possibly user-filled) group/forum stay intact, just unlinked.
        $audience->delete();

        return redirect()->route('admin.audiences.index');
    }

    private function validateAudience(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'emoji' => ['nullable', 'string', 'max:8'],
            'tagline' => ['required', 'string', 'max:120'],
            'intro' => ['required', 'string', 'max:600'],
            'body' => ['required', 'string', 'max:6000'],
            'tips' => ['nullable', 'string', 'max:2000'],
            'color_from' => ['nullable', 'string', 'regex:/^#([0-9A-Fa-f]{6})$/'],
            'color_to' => ['nullable', 'string', 'regex:/^#([0-9A-Fa-f]{6})$/'],
        ]);
    }

    private function splitTips(string $tips): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $tips))
            ->map(fn ($t) => trim($t))
            ->filter()
            ->values()
            ->all();
    }

    private function uniqueAudienceSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'doelgroep';
        $slug = $base;
        $i = 1;
        while (Audience::where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
