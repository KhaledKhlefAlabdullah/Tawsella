<?php


use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('TaxiMovement.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('customerCancelMovement.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('foundCustomer.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('movementCompleted.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

// For accept movement request
Broadcast::channel('driver.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('customer.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});
