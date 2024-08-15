<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// send messages event channle
Broadcast::channel('send-message.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});


Broadcast::channel('Notification-to-user.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});
