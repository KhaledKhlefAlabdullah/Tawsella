<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OurServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\MovementController;


Route::post('/change-password', [AuthenticatedSessionController::class, 'change_password']);

Route::get('/services', [OurServiceController::class, 'index']);

Route::get('/about-us', [AboutUsController::class, 'index']);

// T-26
//chats part
Route::ApiResource('chats', ChatController::class)->except(['show', 'update']);
Route::post('chats/new/{receiver}', [ChatController::class,'store']);

/**
 * Messages Management
 */
Route::get('messages/{chat}', [MessageController::class, 'index']);
Route::ApiResource('messages', MessageController::class)->except(['index','show']);

// T-28
Route::get('/starred-messages', [MessageController::class, 'getAllStarredMessages']);
Route::get('/starred-messages/{chat}', [MessageController::class, 'getChatStarredMessages']);

// T-30
Route::post('/set-message-starred/{message}', [MessageController::class, 'setMessageStarred']);
/**
 * End Messages Management
 */


/**
 * Profile Management
 */
Route::ApiResource('profile', UserProfileController::class)->only(['index', 'update']);
/**
 * Profile management End
 */


/**
 * View my movements
 */
Route::get('my-movements', [MovementController::class, 'myMovements']);
/**
 * End View my movements
 */
