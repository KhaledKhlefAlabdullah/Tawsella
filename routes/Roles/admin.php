<?php

use App\Http\Controllers\OurServiceController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\RolesMiddlewares\AdminMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware([AdminMiddleware::class])->group(function() {

    Route::group(['prefix' => 'users', 'controller' => UsersController::class], function(){

        Route::get('/','index');

        Route::get('/{id}','show');

        Route::post('/add','store');

        Route::put('/edit/{id}','update');

        Route::put('/active-unactive/{id}','setActive');

        Route::delete('/delete/{id}','destroey');

    });

    Route::group(['prefix' => 'services', 'controller' => OurServiceController::class], function(){

        Route::get('/','index');

        Route::post('/add','store');

        Route::put('/edit/{id}','update');

        Route::delete('/delete/{id}','destroey');

    });

});
