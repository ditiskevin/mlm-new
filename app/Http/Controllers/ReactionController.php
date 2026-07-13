<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReactionController extends Controller
{
    /**
     * Toggle a care-reaction for the authenticated user on the given item.
     * - Same emoji again  -> remove the reaction (un-react).
     * - Different emoji    -> update to the new emoji.
     * - No reaction yet    -> create one.
     *
     * A new or changed reaction notifies the item's author (never on removal).
     */
    public function toggle(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(Reaction::TYPES))],
            'id' => ['required'],
            'emoji' => ['required', Rule::in(Reaction::EMOJIS)],
        ]);

        $modelClass = Reaction::TYPES[$data['type']];
        $reactable = $modelClass::query()->findOrFail($data['id']);

        $user = $request->user();

        $existing = Reaction::where('user_id', $user->id)
            ->where('reactable_type', $reactable->getMorphClass())
            ->where('reactable_id', $reactable->getKey())
            ->first();

        if ($existing && $existing->emoji === $data['emoji']) {
            // Same emoji again -> un-react. No notification.
            $existing->delete();

            return back();
        }

        if ($existing) {
            // Different emoji -> replace.
            $existing->update(['emoji' => $data['emoji']]);
        } else {
            // First reaction on this item.
            $reaction = new Reaction(['user_id' => $user->id, 'emoji' => $data['emoji']]);
            $reaction->reactable()->associate($reactable);
            $reaction->save();
        }

        // Notify the item's author about the new / changed reaction.
        if (! empty($reactable->user_id)) {
            Notifier::send(
                recipient: $reactable->user_id,
                type: 'reaction',
                title: $user->name.' reageerde met '.$data['emoji'].' op je bericht',
                url: route('community'),
                icon: '💛',
                actor: $user,
            );
        }

        return back();
    }
}
