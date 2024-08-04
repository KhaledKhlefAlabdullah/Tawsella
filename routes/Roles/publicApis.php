<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OurServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/change-password', [AuthenticatedSessionController::class, 'change_password']);

Route::get('/services', [OurServiceController::class, 'index']);

Route::get('/about-us', [AboutUsController::class, 'index']);

Route::group(['prefix' => 'chats', 'controller' => ChatController::class], function () {
    // T-26
    Route::get('/', 'index');

    Route::group(['prefix' => 'messages', 'controller' => MessageController::class], function () {
        Route::get('/get/{chat_id}', 'index');

        Route::get('/get/starred/{chat_id?}','getStarredMessages');

        Route::post('/send','store');

    });
});
