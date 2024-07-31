<?php

use App\Http\Middleware\RolesMiddlewares\DriverMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware([DriverMiddleware::class])->group(function() {



});
