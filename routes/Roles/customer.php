<?php

use App\Http\Middleware\RolesMiddlewares\CustomerMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxiMovementController;

Route::middleware([CustomerMiddleware::class])->group(function () {
    /**
     * Movements Management
     */
    Route::group(['prefix' => 'movements','movement','controller' => TaxiMovementController::class], function () {
        Route::post('/', 'store');
        Route::post('/cancel/{taxiMovement}', 'canceledMovement');
        Route::get('/latest', 'getLastRequestForCustomer');
    });
    /**
     * End Movements Management
     */
});
