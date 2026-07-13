<?php

namespace App\Http\Controllers;

use App\Models\Babysitter;
use App\Models\BabysitterReview;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BabysitterReviewController extends Controller
{
    public function store(Request $request, Babysitter $babysitter): RedirectResponse
    {
        $user = $request->user();

        // A member may not review their own oppasprofiel.
        if ($babysitter->user_id && $babysitter->user_id === $user->id) {
            return back()->with('error', 'Je kunt geen beoordeling op je eigen profiel plaatsen.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['nullable', 'string', 'max:1500'],
        ]);

        BabysitterReview::updateOrCreate(
            ['babysitter_id' => $babysitter->id, 'user_id' => $user->id],
            [
                'author_name' => $user->name,
                'rating' => $data['rating'],
                'body' => $data['body'] ?? null,
            ],
        );

        // Notify the profile owner when someone else reviews their profile.
        if ($babysitter->user_id && $babysitter->user_id !== $user->id) {
            Notifier::send(
                recipient: $babysitter->user_id,
                type: 'review',
                title: "{$user->name} gaf je oppasprofiel {$data['rating']}★",
                body: isset($data['body']) ? Str::limit($data['body'], 120) : null,
                url: route('babysitters.show', $babysitter->id),
                icon: '⭐',
                actor: $user,
            );
        }

        return back();
    }

    public function destroy(Request $request, BabysitterReview $review): RedirectResponse
    {
        $user = $request->user();
        abort_unless($review->user_id === $user->id || $user->is_admin, 403);

        $review->delete();

        return back();
    }
}
