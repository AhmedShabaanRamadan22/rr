<?php

use App\Models\User;
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
    return (int) $user->id === (int) $idz;
});
Broadcast::channel('ticket', function ($user) {
        return $user->hasRole('superAdmin');
});



// Test Reverb
Broadcast::channel('chat.{id}', function ( User $user, $id) {
    return (int) $user->id === (int) $id || $user->hasRole('superadmin');
});
