<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\OurServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/change-password', [AuthenticatedSessionController::class, 'change_password']);

Route::get('/services',[OurServiceController::class, 'index']);

Route::get('/about-us',[AboutUsController::class, 'index']);
