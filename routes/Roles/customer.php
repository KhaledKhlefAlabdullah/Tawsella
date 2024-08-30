<?php

use App\Http\Middleware\RolesMiddlewares\CustomerMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovementController;


Route::middleware([CustomerMiddleware::class])->group(function() {
    Route::group(['prefix' => 'movements', 'controller' => MovementController::class], function () {
        Route::get('/movements-types', 'movementsTypes');

        Route::get('/nearest-drivers', 'nearestDrivers');

        Route::post('/', 'store');
    });


});
