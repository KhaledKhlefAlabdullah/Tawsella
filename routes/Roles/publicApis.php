<?php

use App\Http\Controllers\ContactUsMessageController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AboutUsController;
use App\Http\Controllers\OfferController;

Route::post('contact-us/send', [ContactUsMessageController::class, 'store']);
Route::get('/about-us', [AboutUsController::class, 'index']);
Route::get('/offers', [OfferController::class, 'index']);
