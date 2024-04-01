<?php

use App\Events\MovementFindUnFindEvent;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactUsMessageController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\TaxiMovementController;
use App\Http\Controllers\TaxiMovementTypeController;
use App\Http\Controllers\UserProfileController;
use App\Models\AboutUs;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\TaxiController;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::post('/found-customer/{id}', [TaxiMovementController::class, 'foundCustomer']);

Route::get('/driver-request/{id}', [TaxiMovementController::class, 'get_request_data']);

Route::post('/get-taxi-location/{driver_id}',[TaxiController::class,'getTaxiLocation']);

Route::middleware('auth:sanctum')->group(function () {

    // for driver
    Route::post('/drivers/set-state', [DriversController::class, 'setState']);

    Route::post('/make-is-completed/{id}', [TaxiMovementController::class, 'makeMovementIsCompleted']);

    Route::group(['prefix' => 'driver'], function () {
    });

    Route::get('/movement-types', [TaxiMovementTypeController::class, 'index']);

    Route::post('/create-taxi-movemet', [TaxiMovementController::class, 'store']);


    Route::group(['prefix' => 'profile'], function () {

        Route::get('/my-profile', [UserProfileController::class, 'index']);

    });
});

Route::post('/profile/edit/{user_id}', [UserProfileController::class, 'update']);

Route::group(['prefix' => 'info'], function () {
    Route::get('/about-us', [AboutUsController::class, 'index']);

    Route::get('/addition', [AboutUsController::class, 'get_addition_information']);
});

Route::get('/movement-types', [TaxiMovementTypeController::class, 'index']);

Route::get('/offers', [OfferController::class, 'index']);

Route::post('/contact-us', [ContactUsMessageController::class, 'store']);

require __DIR__ . '/auth.php';

// Route::post('/test', function () {
//     MovementFindUnFindEvent::dispatch(
//         'driver1',
//         'gdjhs',
//         'msksksk'
//     );
// });
