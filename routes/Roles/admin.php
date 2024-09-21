<?php

use App\Http\Middleware\RolesMiddlewares\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\TaxiController;
use App\Http\Controllers\TaxiMovementController;
use App\Http\Controllers\TaxiMovementTypeController;
use App\Http\Controllers\OurServiceController;

Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    //*****************************Start route movements types******************************** */
    //*********************************************************************************** */
    // todo must change it after add new views
    Route::resource('movement-types', TaxiMovementTypeController::class)->names([
        'index' => 'movement-types',
        'create' => 'movement-types.create',
        'store' => 'movement-types.store',
        'edit' => 'movement-types.edit',
        'update' => 'movement-types.update',
        'destroy' => 'movement-types.destroy',
    ]);

//    Route::group(['prefix' => 'services'], function () {
//        Route::get('/', [TaxiMovementTypeController::class, 'index'])->name('services');
//        Route::get('/cretae', [TaxiMovementTypeController::class, 'create'])->name('service.create');
//        Route::post('/store', [TaxiMovementTypeController::class, 'store'])->name('service.store');
//        Route::get('/edit/{movementType}', [TaxiMovementTypeController::class, 'edit'])->name('service.edit');
//        Route::put('/update/{movementType}', [TaxiMovementTypeController::class, 'update'])->name('service.update');
//        Route::delete('/delete/{movementType}', [TaxiMovementTypeController::class, 'destroy'])->name('service.destroy');
//    });

    //*****************************End route movements types******************************** */
    //************************************************************************************** */

    //*****************************Start route services ******************************** */
    //********************************************************************************** */
    Route::resource('our-services', OurServiceController::class)->names([
        'index' => 'services',
        'create' => 'service.create',
        'store' => 'service.store',
        'edit' => 'service.edit',
        'update' => 'service.update',
        'destroy' => 'service.destroy',
    ]);
    //*****************************End route services ******************************** */
    //******************************************************************************** */

    //***************************start route dashboard ******************************** */
    //********************************************************************************* */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    //***************************End route dashboard ******************************** */
    //******************************************************************************* */

    //***************************start route profile ******************************** */
    //******************************************************************************* */
    Route::resource('profile', UserProfileController::class)->except(['create', 'store'])
        ->names([
            'index' => 'profile.profile',
            'edit' => 'profile.edit',
            'update' => 'profile.update',
            'destroy' => 'profile.destroy'
        ]);
    //***************************End route profile ******************************** */
    //***************************************************************************** */

    //***************************start route driver ******************************** */
//    Route::get('/create-driver', [RegisteredUserController::class, 'create'])
//
//    // For create the drivers acounts
//    Route::post('/store-driver', [RegisteredUserController::class, 'create_driver'])

    Route::resource('drivers', DriversController::class)->names([
        'index' => 'drivers.index',
        // todo need to edit
        'create' => 'drivers.create',
        //->name('create.driver'); old
        'store' => 'drivers.store',
        //->name('store-driver'); old

        'show' => 'drivers.show',
        'edit' => 'drivers.edit',
        'update' => 'drivers.update',
        'destroy' => 'drivers.destroy'
    ]);
    //***************************End route driver ******************************** */
    //**************************************************************************** */

    //***************************start route Taxi ******************************** */
    Route::resource('taxis', TaxiController::class)->names([
        'index' => 'taxis.index',
        'create' => 'taxis.create',
        'store' => 'taxis.store',
        'show' => 'taxis.show',
        'edit' => 'taxis.edit',
        'update' => 'taxis.update',
        'destroy' => 'taxis.destroy'
    ]);
    //*************************** End route Taxi *********************************** */
    //**************************************************************************** */

    //*************************** Start route offers *********************************** */
    Route::resource('offers', OfferController::class)->names([
        'index' => 'offers.index',
        'create' => 'offers.create',
        'store' => 'offers.store',
        'show' => 'offers.show',
        'edit' => 'offers.edit',
        'update' => 'offers.update',
        'destroy' => 'offers.destroy'
    ]);
    //*************************** End route offers *********************************** */
    //**************************************************************************** */


    Route::post('/accept-request/{taxiMovement}', [TaxiMovementController::class, 'acceptRequest'])->name('taxiMovement.accept.request');
    Route::post('/reject-request/{taxiMovement}', [TaxiMovementController::class, 'rejectMovement'])->name('taxiMovement.reject.request');

    //*************************** START route taxi-movement *********************************** */

    // todo need refactoring
    Route::get('/current-taxi-movement', [TaxiMovementController::class, 'LifeTaxiMovements'])->name('current.taxi.movement');

    Route::get('/completed-requests', [TaxiMovementController::class, 'completedTaxiMovements'])->name('completed.requests');

    //*************************** End route taxi-movement *********************************** */
    //**************************************************************************** */

    //*************************** Start route calculations *********************************** */
    Route::group(['prefix' => 'calculations'], function () {
        Route::get('/', [CalculationController::class, 'index'])->name('calculations.index');

        Route::get('/{driver}', [CalculationController::class, 'show'])->name('calculations.show');
        Route::get('/bring/{driver}', [CalculationController::class, 'bring'])->name('calculations.bring');

        Route::get('/create', [CalculationController::class, 'create'])->name('calculations.create');
        Route::post('/store', [CalculationController::class, 'store'])->name('calculations.store');

        Route::get('/edit/{calculations}', [CalculationController::class, 'edit'])->name('calculations.edit');
        Route::put('/update/{calculations}', [CalculationController::class, 'update'])->name('calculations.update');

        Route::delete('/delete/{calculations}', [CalculationController::class, 'destroy'])->name('calculations.destroy');
    });

    //*************************** End route calculations *********************************** */
    //**************************************************************************** */

    //*************************** Start route view maps *********************************** */
    Route::get('/view-completed-movement-map/{taxiMovement}', [TaxiMovementController::class, 'view_map'])->name('movement.completed.map');
    Route::get('/view-life-movement-map/{taxi}', [TaxiController::class, 'viewLifeMap'])->name('movement.life.map');

});

