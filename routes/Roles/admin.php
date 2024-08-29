<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactUsMessageController;
use App\Http\Controllers\OurServiceController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MovementController;
use App\Http\Middleware\RolesMiddlewares\AdminMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware([AdminMiddleware::class])->group(function () {

    /**
     * Users Management
     */
    Route::get('users/drivers', [UsersController::class, 'getDrivers']);

    Route::get('users/customers', [UsersController::class, 'getCustomers']);

    Route::get('users/types', [UsersController::class, 'getUsersTypes']);

    Route::put('users/active-inactive/{user}', [UsersController::class, 'setActive']);

    Route::ApiResource('users', UsersController::class)->except('index');
    /**
     * End Users Management
     */

    /**
     * For Services Management
     */
    Route::ApiResource('services', OurServiceController::class)->except(['index', 'show']);
    /**
     * End Services Management
     */

    /**
     * For About us management
     */
    Route::group(['prefix' => 'about-us', 'controller' => AboutUsController::class], function () {

        Route::post('/general/add-or-update', 'storeOrUpdate');

        Route::post('/additional/add', 'storeAdditionalInfo');

        Route::put('/additional/edit/{aboutUs}', 'updateAdditionalInfo');

        Route::delete('/delete/{aboutUs}', 'destroy');

    });
    /**
     * End About us management
     */

    /**
     * For Contact us management
     */
    Route::group(['prefix' => 'contact-us', 'controller' => ContactUsMessageController::class], function () {
        Route::get('/', 'index');
    });
    /**
     * End Contact us management
     */

    /**
     * For balances management
     */
//    Route::ApiResource('balances', BalanceController::class)->except('show');
    /**
     * End balances management
     */

    /**
     * For Movements view
     */
    Route::get('movements', [MovementController::class, 'index']);
    /**
     * End Movements
     */

});
