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


Broadcast::channel('Taxi-movement.{admin_id}', function ($user, $admin_id) {
    return (int) $user->id === (int) $admin_id;
});

Broadcast::channel('customer.{customer_id}', function ($user, $customer_id) {
    return (int) $user->id === (int) $customer_id;
});

Broadcast::channel('driver.{driver_id}', function ($user, $driver_id) {
    return (int) $user->id === (int) $driver_id;
});

Broadcast::channel('movemnt.{admin_id}', function ($user, $admin_id) {
    return (int) $user->id === (int) $admin_id;
});
