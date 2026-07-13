<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// A member's own private notification stream.
Broadcast::channel('notifications.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Only participants of a conversation may subscribe to its private channel.
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return Conversation::find($conversationId)?->hasParticipant($user) ?? false;
});

// Presence channel for online status + typing indicators within a conversation.
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return (Conversation::find($conversationId)?->hasParticipant($user) ?? false)
        ? ['id' => $user->id, 'name' => $user->name]
        : null;
});
