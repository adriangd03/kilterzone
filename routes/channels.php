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

Broadcast::channel('presence.SendMessageToClientEvent.{id}', function ($user, $id) {
    return $user;
});

Broadcast::channel('presence.ChatMessage.{id}', function ($user, $id) {
    if($user->id == $id){
        return $user;
    }
    return false;
});

Broadcast::channel('presence.UsersOnline', function ($user) {
    return $user;
});

// public channel
Broadcast::channel('ruta.{id}', function ($user) {
    return $user;
});
