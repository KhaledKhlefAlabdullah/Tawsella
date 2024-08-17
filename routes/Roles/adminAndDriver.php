<?php

use App\Http\Controllers\OfferController;
use App\Http\Middleware\RolesMiddlewares\AdminAndDriver;
use Illuminate\Support\Facades\Route;


Route::middleware([AdminAndDriver::class])->group(function() {
    /**
     * Offers Management
     */
    Route::ApiResource('offers', OfferController::class)->except(['index', 'show']);
    /**
     * End Offers Management
     */
});
