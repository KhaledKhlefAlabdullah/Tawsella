<?php

use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'admin'], function() {

    Route::group(['prefix' => 'users', 'controller' => UsersController::class], function(){

        Route::get('/','index');

        Route::get('/{id}','show');

        Route::post('/add','store');

        Route::put('/edit/{id}','update');

        Route::put('/active-unactive/{id}','setActive');

        Route::delete('/delete/{id}','destroey');

    });

});
