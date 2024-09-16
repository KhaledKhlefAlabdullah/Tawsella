<?php

use \App\Http\Middleware\RolesMiddlewares\AdminMiddleware;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaxiController;
use App\Http\Controllers\TaxiMovementController;
use App\Http\Controllers\TaxiMovementTypeController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
         return view('welcom');
     });
// Route::get('/', function () {
//     return view('auth.login');
// });
//***************************start route AppPlatform ******************************** */
Route::get('/Applatform', function () {
    return view('ApplicationPlatform');
});
//*****************************End route AppPlatform ******************************** */
//******************************************************************************* */
Route::middleware(['auth', AdminMiddleware::class])->group(function () {

    //*****************************End route services ******************************** */
    Route::group(['prefix' => 'services'], function () {
        Route::get('/', [TaxiMovementTypeController::class, 'index'])->name('services');
        Route::get('/cretae', [TaxiMovementTypeController::class, 'create'])->name('service.create');
        Route::post('/store', [TaxiMovementTypeController::class, 'store'])->name('service.store');
        Route::get('/edit/{movementType}', [TaxiMovementTypeController::class, 'edit'])->name('service.edit');
        Route::put('/update/{movementType}', [TaxiMovementTypeController::class, 'update'])->name('service.update');
        Route::delete('/delete/{movementType}', [TaxiMovementTypeController::class, 'destroy'])->name('service.destroy');
    });

    //*****************************End route services ******************************** */
    //******************************************************************************* */


    //************************************ ROUTE **************************************** */
    //************************************ ROUTE **************************************** */

    Route::get('/profiles', function () {
        return view('profile.profile');
    });

    //***************************start route dashboard ******************************** */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //***************************End route dashboard ******************************** */
    //******************************************************************************* */

    //***************************start route profile ******************************** */
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    //***************************End route profile ******************************** */
    //***************************************************************************** */

    //***************************start route driver ******************************** */
    Route::get('/create-driver', [RegisteredUserController::class, 'create'])
        ->name('create.driver');
    // For create the drivers acounts
    Route::post('/store-driver', [RegisteredUserController::class, 'create_driver'])->name('store-driver');
    Route::group(['prefix' => 'drivers'], function () {
        Route::get('/', [DriversController::class, 'index'])->name('drivers.index');
        Route::get('/{driver}', [DriversController::class, 'show'])->name('drivers.show');
        Route::get('/edit/{driver}', [DriversController::class, 'edit'])->name('drivers.edit');
        Route::put('/update/{driver}', [UserProfileController::class, 'update'])->name('drivers.update');
        Route::delete('/delete/{driver}', [DriversController::class, 'destroy'])->name('drivers.destroy');
    });


    // Route::post('/drivers/set-state', [DriversController::class, 'changeDriverState'])->name('drivers.set_state');

    //***************************End route driver ******************************** */
    //**************************************************************************** */

    //***************************start route Taxi ******************************** */
    Route::group(['prefix' => 'taxis'], function () {
        Route::get('/', [TaxiController::class, 'index'])->name('taxis.index');

        Route::get('/create', [TaxiController::class, 'create'])->name('taxis.create');
        Route::post('/store', [TaxiController::class, 'store'])->name('taxis.store');

        Route::get('/edit/{taxi}', [TaxiController::class, 'edit'])->name('taxis.edit');
        Route::put('/update/{taxi}', [TaxiController::class, 'update'])->name('taxis.update');

        Route::delete('/delete/{id}', [TaxiController::class, 'destroy'])->name('taxis.destroy');
    });

    //*************************** End route Taxi *********************************** */
    //**************************************************************************** */

    //*************************** Start route offers *********************************** */
    Route::group(['prefix' => 'offers'], function () {
        Route::get('/', [OfferController::class, 'index'])->name('offers.index');

        Route::get('/create', [OfferController::class, 'create'])->name('offers.create');
        Route::post('/store', [OfferController::class, 'store'])->name('offers.store');

        Route::get('/edit/{offer}', [OfferController::class, 'edit'])->name('offers.edit');
        Route::put('/update/{offer}', [OfferController::class, 'update'])->name('offers.update');

        Route::delete('/delete/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');
    });

    //*************************** End route offers *********************************** */
    //**************************************************************************** */


    Route::post('/accept-reject-request/{id}', [TaxiMovementController::class, 'accept_reject_request'])->name('accept.reject.request');

    //*************************** START route taxi-movement *********************************** */


    Route::get('/current-taxi-movement', [TaxiMovementController::class, 'currentTaxiMovement'])->name('current.taxi.movement');

    Route::get('/completed-requests', [TaxiMovementController::class, 'completedRequests'])->name('completed.requests');

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

    Route::get('/view-map/{selector}/{id}', [TaxiMovementController::class, 'view_map'])->name('map');
});



require __DIR__ . '/auth.php';
