<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactUsMessageController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\TaxiController;
use App\Http\Controllers\TaxiMovementController;
use App\Http\Controllers\TaxiMovementTypeController;
use App\Http\Controllers\UserProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
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

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::post('/found-customer/{id}', [TaxiMovementController::class, 'foundCustomer']);

Route::get('/driver-request/{id}', [TaxiMovementController::class, 'get_request_data']);

Route::post('/get-taxi-location/{driver_id}',[TaxiController::class,'getTaxiLocation']);

Route::middleware(['auth:sanctum', 'verified.email'])->group(function () {

    // for driver
    Route::post('/drivers/set-state', [DriversController::class, 'changeDriverState']);

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

    Route::get('/addition', [AboutUsController::class, 'getAdditionInformation']);
});

Route::get('/movement-types', [TaxiMovementTypeController::class, 'index']);

Route::get('/get-car', [TaxiMovementTypeController::class, 'getMovement3']);

Route::get('/offers', [OfferController::class, 'index']);

Route::post('/contact-us', [ContactUsMessageController::class, 'store']);

Route::get('/phone',function(){

    $phone = User::where('user_type','admin')->first()->user_profile->phoneNumber ?? '+3520000000';

    return $phone;
});


require __DIR__ . '/auth.php';

// Route::post('/test', function () {
//     MovementFindUnFindEvent::dispatch(
//         'driver1',
//         'gdjhs',
//         'msksksk'
//     );
// });
