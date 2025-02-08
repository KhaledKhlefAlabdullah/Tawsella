<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('customerLocation.{id}', function ($user, $id) {
    return  $user->id ==  $id;
});

Broadcast::channel('TaxiLocation.{id}', function ($user, $id) {
    return $user->id == $id;
});
