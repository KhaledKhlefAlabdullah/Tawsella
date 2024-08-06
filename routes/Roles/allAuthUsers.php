<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OurServiceController;
use Illuminate\Support\Facades\Route;


Route::post('/change-password', [AuthenticatedSessionController::class, 'change_password']);

Route::get('/services', [OurServiceController::class, 'index']);

Route::get('/about-us', [AboutUsController::class, 'index']);

Route::group(['prefix' => 'chats', 'controller' => ChatController::class], function () {
    // T-26
    Route::get('/', 'index');

    Route::group(['prefix' => 'messages', 'controller' => MessageController::class], function () {
        // T-27
        Route::get('/get/{chat_id}', 'index');
        // T-28
        Route::get('/get/starred/{chat_id?}', 'getStarredMessages');
        // T-29
        Route::post('/send', 'store');
        // T-30
        Route::post('/set-starred/{id}', 'setMessageStarred');
        // T-31
        Route::post('/edit/{id}', 'update');
        // T-32
        Route::delete('/delete/{id}', 'destroy');

    });
});

