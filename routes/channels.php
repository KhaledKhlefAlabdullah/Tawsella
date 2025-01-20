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

Broadcast::routes(['middleware' => ['auth:sanctum']]);  // Use API auth for token-based authentication

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('test', function () {
    return  true;
});

Broadcast::channel('Taxi-movement.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});


Broadcast::channel('TaxiLocation.{id}', function ($user, $id) {

    return $user->id == $id;
});

Broadcast::channel('admin-customer-cancel.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('driver-customer-cancel.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('found-customer.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('driver.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('customer.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('send-message.{id}', function ($user, $id) {
    $chat = \App\Models\ChatMember::where(['chat_id' => $id, 'member_id' => $user->id])->first();
    return  $chat != null;
});
