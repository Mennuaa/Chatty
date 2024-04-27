<?php

use App\Models\Conversation;
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
// Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
//     return (int) $user->id === (int) Conversation::find($conversationId)->sender_id ||
//            (int) $user->id === (int) Conversation::find($conversationId)->receiver_id;
// });

// Broadcast::channel('private-conversation.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
