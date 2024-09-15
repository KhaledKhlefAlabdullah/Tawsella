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

// send driver location to dashboard or to the customer to see where is the driver on map
Broadcast::channel('driver-location.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});

// send customer location to the driver to see where is the customer on map
Broadcast::channel('customer-location.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});


// send movement request to driver
Broadcast::channel('movement-request-to.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});

// send new driver state to dashboard
Broadcast::channel('driver-change-state.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});
