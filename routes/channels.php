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
    return  $user->id ===  $id;
});


Broadcast::channel('Taxi-movement.{admin_id}', function ($user, $admin_id) {
    return  $user->id ===  $admin_id;
});


Broadcast::channel('TaxiLocation.{admin_id}', function ($user, $admin_id) {

    return $user->id === $admin_id;
});

Broadcast::channel('customer.{customer_id}', function ($user, $customer_id) {
    return  $user->id ===  $customer_id;
});

Broadcast::channel('driver.{driver_id}', function ($user, $driver_id) {
    return  $user->id ===  $driver_id;
});

Broadcast::channel('movemnt.{admin_id}', function ($user, $admin_id) {
    return  $user->id ===  $admin_id;
});
