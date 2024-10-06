<?php

use App\Http\Controllers\ContactUsMessageController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\RolesMiddlewares\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\OfferController;
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
    Route::apiResource('our-services', OurServiceController::class)->only(['store', 'destroy']);
    Route::post('our-services/{our_service}', [OurServiceController::class, 'update']);
    //*****************************End route services ******************************** */
    //******************************************************************************** */

    //***************************start route driver ******************************** */
    //****************************************************************************** */
    Route::apiResource('drivers', DriversController::class);
    Route::get('drivers-ready', [DriversController::class, 'getReadiesDrivers']);
    //***************************End route driver ******************************** */
    //**************************************************************************** */

    //***************************start route Taxi ********************************** */
    //****************************************************************************** */
    Route::apiResource('taxis', TaxiController::class)->except(['show']);
    Route::get('taxis/view-life-movement-map/{taxi}', [TaxiController::class, 'viewLifeMap']);
    //*************************** End route Taxi *********************************** */
    //****************************************************************************** */

    //*************************** Start route offers *********************************** */
    //********************************************************************************** */
    Route::apiResource('offers', OfferController::class)->except(['index', 'show']);
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

    Route::post('calculations/bring/{driver}', [CalculationController::class, 'bring']);
    //*************************** End route calculations *********************************** */
    //************************************************************************************** */

    //*************************** Start route report ************************************** */
    //************************************************************************************* */
    Route::group(['prefix' => 'dashboard', 'controller' => DashboardController::class], function () {
        Route::get('/', 'index');
        Route::get('/movement-details/{taxiMovement}', [DashboardController::class, 'getMovementDetails']);
        Route::get('/report', 'viewReport');
        Route::get('/report/download', 'downloadReport');
    });
    //*************************** End route report **************************************** */
    //************************************************************************************* */
    //*************************** Start route contact us ************************************** */
    //************************************************************************************* */
    Route::apiResource('contact-us', ContactUsMessageController::class)->except(['store', 'update']);
    Route::post('contact-us/answer/{contact_us}', [ContactUsMessageController::class, 'answer']);
    //*************************** End route contact us **************************************** */
    //************************************************************************************* */
});

