<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactUsMessageController;
use App\Http\Controllers\OurServiceController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\RolesMiddlewares\AdminMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware([AdminMiddleware::class])->group(function() {

    /**
     * Users Management
     */
    Route::ApiResource('users', UsersController::class);

    Route::get('/users-types',[UsersController::class, 'getUsersTypes']);

    Route::put('/active-unactive/{user}',[UsersController::class, 'setActive']);
    /**
     * End Users Management
     */
    Route::group(['prefix' => 'services', 'controller' => OurServiceController::class], function(){

        Route::post('/add','store');

        Route::post('/edit/{service}','update');

        Route::delete('/delete/{service}','destroy');

    });

    Route::group(['prefix' => 'about-us', 'controller' => AboutUsController::class], function(){

        Route::post('/general/add-or-update','storeOrUpdate');

        Route::post('/additional/add','storeAdditionalInfo');

        Route::put('/additional/edit/{aboutUs}','updateAdditionalInfo');

        Route::delete('/delete/{aboutUs}','destroy');

    });

    Route::group(['prefix' => 'contact-us', 'controller' => ContactUsMessageController::class], function(){
        Route::get('/','index');
    });

});
