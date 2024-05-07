<?php

use App\Http\Middleware\RolesMiddlewares\AllAuthUsersExceptDriver;
use Illuminate\Support\Facades\Route;


Route::middleware([AllAuthUsersExceptDriver::class])->group(function() {

});
