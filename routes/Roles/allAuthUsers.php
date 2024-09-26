<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;

/**
 * Profile Management
 */
Route::group(['prefix' => 'profile', 'controller' => UserProfileController::class], function () {
    Route::get('/', 'index');
    Route::post('/', 'update');
});
/**
 * End Profile Management
 */

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
