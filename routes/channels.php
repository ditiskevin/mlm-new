<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Only participants of a conversation may subscribe to its private channel.
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return Conversation::find($conversationId)?->hasParticipant($user) ?? false;
});
