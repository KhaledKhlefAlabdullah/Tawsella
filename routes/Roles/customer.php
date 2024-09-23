<?php

use App\Http\Middleware\RolesMiddlewares\CustomerMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxiMovementTypeController;
use App\Http\Controllers\TaxiMovementController;

Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::get('/movement-types', [TaxiMovementTypeController::class, 'index']);
    /**
     * Movements Management
     */
    Route::group(['movement','controller' => TaxiMovementController::class], function () {
        // todo edit in create taxi movement in front
        Route::post('/', 'store');
        // todo add it to front
        Route::post('cancel-taxi-movement', 'canceledMovement');
    });
    /**
     * End Movements Management
     */
});
