<?php

use App\Http\Controllers\ContactUsMessageController;
use Illuminate\Support\Facades\Route;

Route::post('contact-us/send', [ContactUsMessageController::class, 'store']);
