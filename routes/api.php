<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctom')->group(function () {

    Route::middleware('driver')->group(function () {

        Route::group(['prefix' => 'driver'], function () {

        });
    });

    Route::middleware('customer')->group(function () {

        Route::group(['prefix' => 'customer'], function () {
            
        });
    });
});

require __DIR__ . '/auth.php';
