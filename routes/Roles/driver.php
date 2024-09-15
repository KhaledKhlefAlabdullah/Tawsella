<?php

use App\Http\Middleware\RolesMiddlewares\DriverMiddleware;
use \App\Http\Controllers\MovementController;
use Illuminate\Support\Facades\Route;


Route::middleware([DriverMiddleware::class])->group(function() {
    /**
     * Manage movements
     */
    Route::group(['prefix' => 'movements', 'controller' => MovementController::class], function () {
        Route::post('movement/accept/{movement}','acceptMovement');
        Route::post('movement/reject/{movement}','rejectMovement');
        Route::post('movement/mark-completed/{movement}','markMovementIsCompleted');
    });
    /**
     * End movements manage
     */
});
