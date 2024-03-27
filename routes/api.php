<?php

use App\Events\MenementFindUnFindEvent;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactUsMessageController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\TaxiMovementController;
use App\Http\Controllers\TaxiMovementTypeController;
use App\Http\Controllers\UserProfileController;
use App\Models\AboutUs;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriversController;

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
Route::post('/found-customer/{id}', [TaxiMovementController::class, 'foundCostumer'])->name('found.customer');


Route::post('/drivers/set-state', [DriversController::class, 'setState']);

Route::middleware('auth:sanctum')->group(function () {


    Route::middleware('driver')->group(function () {

        Route::group(['prefix' => 'driver'], function () {
        });
    });

    Route::middleware('customer')->group(function () {

        Route::get('/movement-types', [TaxiMovementTypeController::class, 'index']);

        Route::post('/create-taxi-movemet', [TaxiMovementController::class, 'store']);

    });

    Route::group(['prefix' => 'profile'],function(){

        Route::get('/my-profile',[UserProfileController::class,'index']);

        Route::post('/edit/{user_id}',[UserProfileController::class,'update']);

    });
});

Route::group(['prefix' => 'info'], function () {
    Route::get('/about-us', [AboutUsController::class, 'index']);

    Route::get('/addition', [AboutUsController::class, 'get_addition_information']);
});

Route::get('/offers',[OfferController::class,'index']);

Route::post('/contact-us',[ContactUsMessageController::class,'store']);
require __DIR__ . '/auth.php';

// Route::post('/test', function () {
//     MenementFindUnFindEvent::dispatch(
//         'driver1',
//         'gdjhs',
//         'msksksk'
//     );
// });
