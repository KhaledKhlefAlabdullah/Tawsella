<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\CheckUserIsActiveMiddleware;

Route::middleware([CheckUserIsActiveMiddleware::class])->group(function () {
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {

        // admin routes
        require __DIR__ . '/Roles/admin.php';

        // customer routes
        require __DIR__ . '/Roles/customer.php';

        // driver routes
        require __DIR__ . '/Roles/driver.php';

        // public routes
        require __DIR__ . '/Roles/allAuthUsers.php';

        // users except drivers
        require __DIR__ . '/Roles/exceptDrivers.php';

        // admin and driver
        require __DIR__ . '/Roles/adminAndDriver.php';
    });

// for all users auth or not auth
    require __DIR__ . '/Roles/publicApis.php';
});
require __DIR__ . '/auth.php';

Route::get('/', function () {
    return \App\Enums\MovementRequestStatus::getMovementStatuses();
});
