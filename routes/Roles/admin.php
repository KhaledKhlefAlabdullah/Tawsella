<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DashboardController;
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
    Route::apiResource('movement-types', TaxiMovementTypeController::class)->except(['index', 'show']);
    //*****************************End route movements types******************************** */
    //************************************************************************************** */

    //*****************************Start route services ******************************** */
    //********************************************************************************** */
    Route::apiResource('our-services', OurServiceController::class);
    //*****************************End route services ******************************** */
    //******************************************************************************** */

    //***************************start route profile ******************************** */
    //******************************************************************************* */
    Route::apiResource('profile', UserProfileController::class)->except(['create', 'store']);
    //***************************End route profile ******************************** */
    //***************************************************************************** */

    //***************************start route driver ******************************** */
    //****************************************************************************** */
    Route::apiResource('drivers', DriversController::class);

    Route::put('password-driver/{driver}', [PasswordController::class, 'updateDriverPassword']);
    //***************************End route driver ******************************** */
    //**************************************************************************** */

    //***************************start route Taxi ******************************** */
    Route::apiResource('taxis', TaxiController::class);
    //*************************** End route Taxi *********************************** */
    //****************************************************************************** */

    //*************************** Start route offers *********************************** */
    //********************************************************************************** */
    Route::apiResource('offers', OfferController::class);
    //*************************** End route offers *********************************** */
    //******************************************************************************** */

    //*************************** START route taxi-movement *********************************** */
    //***************************************************************************************** */
    Route::group(['prefix' => 'taxi-movement', 'controller' => TaxiMovementController::class], function () {
        Route::get('/current', 'LifeTaxiMovements');

        Route::get('/completed', 'completedTaxiMovements');

        Route::post('/accept/{taxiMovement}', 'acceptRequest');

        Route::post('/reject/{taxiMovement}', 'rejectMovement');

        Route::get('/view-completed-movement-map/{taxiMovement}', 'view_map');
    });
    //*************************** End route taxi-movement *********************************** */
    //*************************************************************************************** */

    //*************************** Start route calculations *********************************** */
    //**************************************************************************************** */
    Route::apiResource('calculations', CalculationController::class)->only(['index', 'destroy']);

    Route::get('calculations/bring/{driver}', [CalculationController::class, 'bring']);
    //*************************** End route calculations *********************************** */
    //************************************************************************************** */

    //*************************** Start route view maps *********************************** */
    //************************************************************************************* */
    Route::get('/view-life-movement-map/{taxi}', [TaxiController::class, 'viewLifeMap']);
    //*************************** End route view maps ************************************** */
    //************************************************************************************** */

    //*************************** Start route report ************************************** */
    //************************************************************************************* */
    Route::group(['prefix' => 'dashboard', 'controller' => DashboardController::class], function () {
        Route::get('/', 'index');
        Route::get('/report', 'viewReport');

        Route::get('/report/download', 'downloadReport');
    });
    //*************************** End route report **************************************** */
    //************************************************************************************* */
});

