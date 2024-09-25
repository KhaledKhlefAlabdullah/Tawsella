<?php

use App\Http\Middleware\RolesMiddlewares\CustomerMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxiMovementController;

Route::middleware([CustomerMiddleware::class])->group(function () {
    /**
     * Movements Management
     */
    Route::group(['prefix' => 'movements','movement','controller' => TaxiMovementController::class], function () {
        // todo edit in create taxi movement in front
        Route::post('/', 'store');
        // todo add it to front
        Route::post('/cancel', 'canceledMovement');
    });
    /**
     * End Movements Management
     */
});
