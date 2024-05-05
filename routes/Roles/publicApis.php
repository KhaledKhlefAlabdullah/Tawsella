<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/change-password', [AuthenticatedSessionController::class, 'change_password']);
