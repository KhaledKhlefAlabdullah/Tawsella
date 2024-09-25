<?php

use App\Http\Controllers\ContactUsMessageController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AboutUsController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\TaxiMovementTypeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OurServiceController;

/**
 * Profile Management
 */
// todo make edit apis in front
Route::group(['prefix' => 'profile', 'controller' => UserProfileController::class], function () {
    Route::get('/', 'index');
    Route::post('/', 'update');
});
/**
 * End Profile Management
 */

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


Route::apiResource('our-services', OurServiceController::class)->only(['index', 'show']);

/**
 * Chats Management
 */
Route::apiResource('chats', ChatController::class)->except(['show', 'update']);
Route::post('chats/new/{receiver}', [ChatController::class, 'store']);
/**
 * End Chats Management
 */

/**
 * Messages Management
 */
Route::get('messages/{chat}', [MessageController::class, 'index']);
Route::apiResource('messages', MessageController::class)->except(['index', 'show']);

// T-28
Route::get('/starred-messages', [MessageController::class, 'getAllStarredMessages']);
Route::get('/starred-messages/{chat}', [MessageController::class, 'getChatStarredMessages']);

// T-30
Route::post('/set-message-starred/{message}', [MessageController::class, 'setMessageStarred']);
/**
 * End Messages Management
 */
