<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private chat channel authorization
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = \App\Models\Chat::find($chatId);

    if (!$chat) {
        return false;
    }

    // Allow if user is the customer or an admin
    return $user->id === $chat->customer_id || $user->hasRole('admin');
});

// User-specific channel for notifications
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Admin channel (for all admin notifications)
Broadcast::channel('user.admin', function ($user) {
    return $user->hasRole('admin');
});
