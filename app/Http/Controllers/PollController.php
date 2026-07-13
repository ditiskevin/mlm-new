<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:200'],
            'options' => ['required', 'array', 'min:2', 'max:6'],
            'options.*' => ['nullable', 'string', 'max:80'],
            'community_group_id' => ['nullable', 'exists:community_groups,id'],
        ]);

        // Keep only non-empty option labels, but require at least two.
        $labels = collect($data['options'])
            ->map(fn ($label) => is_string($label) ? trim($label) : '')
            ->filter(fn ($label) => $label !== '')
            ->values();

        if ($labels->count() < 2) {
            return back()->withErrors([
                'options' => 'Een poll heeft minimaal twee opties nodig.',
            ]);
        }

        $user = $request->user();

        $poll = Poll::create([
            'user_id' => $user->id,
            'community_group_id' => $data['community_group_id'] ?? null,
            'question' => $data['question'],
            'author_name' => $user->name,
            'avatar_color' => $user->avatar_color ?: '#F7A8B5',
        ]);

        $labels->each(fn (string $label, int $index) => $poll->options()->create([
            'label' => $label,
            'position' => $index,
        ]));

        return back();
    }

    public function vote(Request $request, Poll $poll): RedirectResponse
    {
        $data = $request->validate([
            'poll_option_id' => [
                'required',
                'integer',
                // The chosen option must belong to this poll.
                function ($attribute, $value, $fail) use ($poll) {
                    if (! $poll->options()->whereKey($value)->exists()) {
                        $fail('Deze optie hoort niet bij deze poll.');
                    }
                },
            ],
        ]);

        $user = $request->user();

        // One vote per poll per user; changing the vote replaces the old row.
        PollVote::where('poll_id', $poll->id)
            ->where('user_id', $user->id)
            ->delete();

        PollVote::create([
            'poll_id' => $poll->id,
            'poll_option_id' => $data['poll_option_id'],
            'user_id' => $user->id,
        ]);

        return back();
    }

    public function destroy(Request $request, Poll $poll): RedirectResponse
    {
        $user = $request->user();
        abort_unless($poll->user_id === $user->id || $user->is_admin, 403);

        $poll->delete();

        return back();
    }
}
