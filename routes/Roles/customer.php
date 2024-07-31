<?php

use App\Http\Middleware\RolesMiddlewares\CustomerMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware([CustomerMiddleware::class])->group(function() {



});
