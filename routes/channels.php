<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('messages.{clientId}.{catererId}', function ($user, $clientId, $catererId) {
    return ((int) $user->id === (int) $clientId && $user->role === 'client')
        || ((int) $user->id === (int) $catererId && $user->role === 'caterer');
});
