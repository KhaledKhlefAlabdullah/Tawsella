<?php

use App\Http\Controllers\ContactUsMessageController;
use App\Http\Controllers\DriversController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AboutUsController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\TaxiMovementTypeController;
use App\Http\Controllers\OurServiceController;
/**
 * Info Management
 */
Route::get('/about-us', [AboutUsController::class, 'index']);
/**
 * End info Management
 */

Route::apiResource('movement-types', TaxiMovementTypeController::class)->only(['index', 'show']);

Route::apiResource('offers', OfferController::class)->only(['index', 'show']);

Route::post('contact-us', [ContactUsMessageController::class, 'store']);

Route::get('/phone', function () {
    $phone = \App\Models\User::role(\App\Enums\UserEnums\UserType::Admin()->key)->first()->user_profile->phone_number ?? '+3520000000';
    return $phone;
});

Route::apiResource('social-links', \App\Http\Controllers\SocialLinksController::class)->only(['index', 'show']);

Route::get('/getKMPrice', [\App\Http\Controllers\TaxiMovementTypeController::class, 'getKMPrice']);

Route::get('drivers-ready', [DriversController::class, 'getReadiesDrivers']);

Route::apiResource('advertisements', \App\Http\Controllers\AdvertisementController::class)->only(['index', 'show']);

Route::get('api-key', [\App\Http\Controllers\MapApiKeyController::class, 'index']);
