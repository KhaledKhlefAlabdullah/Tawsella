<?php

use App\Http\Controllers\TaxiMovementController;
use App\Http\Controllers\TaxiMovementTypeController;
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

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('driver')->group(function () {

        Route::group(['prefix' => 'driver'], function () {

        });
    });

    Route::middleware('customer')->group(function () {

        Route::get('/movement-types',[TaxiMovementTypeController::class,'index']);

        Route::group(['prefix' => 'customer'], function () {
            
            Route::post('/create-taxi-movemet',[TaxiMovementController::class,'store']);

        });
    });
});

require __DIR__ . '/auth.php';
