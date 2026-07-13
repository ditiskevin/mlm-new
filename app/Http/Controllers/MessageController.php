<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\User;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
    /**
     * Inbox. Optionally renders an open conversation thread.
     */
    public function index(Request $request, ?Conversation $conversation = null): Response
    {
        $user = $request->user();

        if ($conversation && ! $conversation->exists) {
            $conversation = null;
        }

        if ($conversation) {
            $this->authorizeParticipant($conversation, $user);
            $this->markRead($conversation, $user);
        }

        return Inertia::render('Messages/Index', [
            'conversations' => $this->inbox($user),
            'active' => $conversation ? $this->thread($conversation, $user) : null,
        ]);
    }

    /**
     * Start (or reopen) a 1-to-1 conversation with another member.
     */
    public function startWith(Request $request, User $user): RedirectResponse
    {
        $me = $request->user();

        if ($user->id === $me->id) {
            return redirect()->route('messages.index')->with('error', 'Je kunt geen bericht naar jezelf sturen.');
        }

        if ($me->hasBlocked($user) || $user->hasBlocked($me)) {
            return redirect()->route('messages.index')->with('error', 'Je kunt geen bericht sturen naar dit lid.');
        }

        $conversation = Conversation::between($me, $user);

        return redirect()->route('messages.show', $conversation);
    }

    public function store(Request $request, Conversation $conversation): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeParticipant($conversation, $user);

        $other = $conversation->participants()->where('users.id', '!=', $user->id)->first();
        abort_if($other && ($user->hasBlocked($other) || $other->hasBlocked($user)), 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:4000'],
        ]);

        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'body' => $data['body'],
        ]);

        $conversation->forceFill(['last_message_at' => now()])->save();
        $this->markRead($conversation, $user);

        // Push to the conversation channel in real time (ShouldBroadcastNow).
        // The sender already has the message locally and dedupes by id.
        MessageSent::dispatch($message);

        // Notify the other participant(s) about the new message.
        $conversation->participants()
            ->where('users.id', '!=', $user->id)
            ->get()
            ->each(fn (User $other) => Notifier::send(
                recipient: $other,
                type: 'message',
                title: $user->name.' stuurde je een bericht',
                body: \Illuminate\Support\Str::limit($data['body'], 60),
                url: route('messages.show', $conversation),
                icon: '💬',
                actor: $user,
            ));

        return back();
    }

    private function authorizeParticipant(Conversation $conversation, User $user): void
    {
        abort_unless($conversation->hasParticipant($user), 403);
    }

    private function markRead(Conversation $conversation, User $user): void
    {
        $now = now();
        $conversation->participants()->updateExistingPivot($user->id, ['last_read_at' => $now]);

        // Let the other participant's open thread show a "gelezen" receipt.
        \App\Events\ConversationRead::dispatch($conversation->id, $user->id, $now->toIso8601String());
    }

    /**
     * Conversation list for the sidebar, newest activity first.
     */
    private function inbox(User $user): array
    {
        return $user->conversations()
            ->with(['participants:id,name,avatar_color,avatar_path', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->orderByRaw('COALESCE(last_message_at, conversations.created_at) DESC')
            ->get()
            ->map(function (Conversation $c) use ($user) {
                $other = $c->participants->firstWhere('id', '!=', $user->id);
                $last = $c->messages->first();
                $lastReadAt = $c->pivot->last_read_at;
                $unread = $last
                    && $last->user_id !== $user->id
                    && ($lastReadAt === null || $last->created_at->gt($lastReadAt));

                return [
                    'id' => $c->id,
                    'name' => $other?->name ?? 'Onbekend lid',
                    'avatar_color' => $other?->avatar_color ?? '#F7A8B5',
                    'avatar_url' => $other?->avatar_url,
                    'initial' => mb_substr($other?->name ?? '?', 0, 1),
                    'preview' => $last ? \Illuminate\Support\Str::limit($last->body, 48) : 'Nog geen berichten',
                    'when' => $last?->created_at->diffForHumans(),
                    'unread' => $unread,
                ];
            })
            ->all();
    }

    private function thread(Conversation $conversation, User $user): array
    {
        $conversation->load(['participants:id,name,avatar_color,avatar_path', 'messages.sender:id,name,avatar_color']);
        $other = $conversation->participants->firstWhere('id', '!=', $user->id);
        $otherLastRead = $other?->pivot?->last_read_at;

        return [
            'id' => $conversation->id,
            'other' => [
                'id' => $other?->id,
                'name' => $other?->name ?? 'Onbekend lid',
                'avatar_color' => $other?->avatar_color ?? '#F7A8B5',
                'avatar_url' => $other?->avatar_url,
                'initial' => mb_substr($other?->name ?? '?', 0, 1),
            ],
            'other_last_read' => $otherLastRead ? \Illuminate\Support\Carbon::parse($otherLastRead)->toIso8601String() : null,
            'messages' => $conversation->messages->map(fn ($m) => [
                'id' => $m->id,
                'body' => $m->body,
                'mine' => $m->user_id === $user->id,
                'author' => $m->sender?->name,
                'when' => $m->created_at->diffForHumans(),
                'at' => $m->created_at->toIso8601String(),
            ])->values(),
        ];
    }
}
