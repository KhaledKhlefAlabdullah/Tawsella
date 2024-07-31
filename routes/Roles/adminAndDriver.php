<?php

use App\Http\Controllers\OfferController;
use App\Http\Middleware\RolesMiddlewares\AdminAndDriver;
use Illuminate\Support\Facades\Route;


Route::middleware([AdminAndDriver::class])->group(function() {

    Route::group(['prefix' => 'offers', 'controller' => OfferController::class], function () {

        Route::post('/add','store');

        Route::put('/edit/{id}','update');

        Route::delete('/delete/{id}','destroy');
    });

});
