<?php

use App\Http\Middleware\RolesMiddlewares\DriverMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\TaxiController;
use App\Http\Controllers\TaxiMovementController;


Route::middleware([DriverMiddleware::class])->group(function() {
    /**
     * Movements Management
     */
    Route::group(['controller' => TaxiMovementController::class], function () {
        Route::post('/found-customer/{taxiMovement}', 'foundCustomer');
        Route::get('/driver-request/{id}', 'get_request_data');
        Route::post('/make-is-completed/{taxiMovement}', 'makeMovementIsCompleted');
    });
    /**
     * End Movements Management
     */

    Route::post('/get-taxi-location/{driver}',[TaxiController::class,'getTaxiLocation']);

    Route::post('/drivers/set-state', [DriversController::class, 'changeDriverState']);
});
