<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaxiController;
use App\Http\Controllers\TaxiMovementController;
use App\Http\Controllers\TaxiMovementTypeController;
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
    return view('auth.login');
});

//*****************************End route Servises ******************************** */
Route::get('/serve', [TaxiMovementTypeController::class, 'index']);
//*****************************End route Servises ******************************** */
//******************************************************************************* */

//************************************ ROUTE **************************************** */
//************************************ ROUTE **************************************** */

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/profiles', function () {
        return view('profile.profile');
    });
    //***************************start route Contact ******************************** */
    Route::get('/Contact', function () {
        return view('Contact');
    });
    //*****************************End route Contact ******************************** */
    //******************************************************************************* */
    //***************************start route dashboard ******************************** */
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    //***************************End route dashboard ******************************** */
    //******************************************************************************* */

    //***************************start route profile ******************************** */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //***************************End route profile ******************************** */
    //***************************************************************************** */

    //***************************start route driver ******************************** */
    Route::get('/create-driver', [RegisteredUserController::class, 'create'])
        ->name('create.driver');
    // For create the drivers acounts
    Route::post('/store-driver', [RegisteredUserController::class, 'admin_store'])->name('store-driver');
    Route::group(['prefix' => 'drivers'], function () {
        Route::get('/', [DriversController::class, 'index']);
    });
    Route::get('/drivers/{id}', [DriversController::class, 'show'])->name('drivers.show');

    Route::get('/drivers/{id}/edit', [DriversController::class, 'edit'])->name('drivers.edit');
    Route::put('/drivers/{id}', [DriversController::class, 'update'])->name('drivers.update');
    //***************************End route driver ******************************** */
    //**************************************************************************** */

    //***************************start route Taxi ******************************** */
    Route::get('/taxi', [TaxiController::class, 'index'])->name('taxis.index');
    Route::get('/taxi/{taxi}', [TaxiController::class, 'show'])->name('taxis.show');
    Route::get('/taxis/create',[TaxiController::class,'create'])->name('taxis.create');
    Route::post('/taxis', [TaxiController::class, 'store'])->name('taxis.store');

    Route::get('/taxis/{taxi}/edit', [TaxiController::class, 'edit'])->name('taxis.edit');
    Route::put('/taxis/{taxi}', [TaxiController::class, 'update'])->name('taxis.update');

    Route::delete('/taxis/{taxi}', [TaxiController::class, 'destroy'])->name('taxis.destroy');
    //*************************** End route Taxi *********************************** */
    //**************************************************************************** */

    //*************************** Start route offers *********************************** */
    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');

    Route::get('/offers/create', [OfferController::class, 'create'])->name('offers.create');
    Route::post('/offers', [OfferController::class, 'store'])->name('offers.store');

    Route::get('/offers/{offer}/edit', [OfferController::class, 'edit'])->name('offers.edit');
    Route::put('/offers/{offer}', [OfferController::class, 'update'])->name('offers.update');

    Route::delete('/offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');
    //*************************** End route offers *********************************** */
    //**************************************************************************** */

    Route::post('/accept-reject-request/{taxiMovement}',[TaxiMovementController::class,'accept_reject_request'])->name('accept.reject.request');
    
    //*************************** START route taxi-movement *********************************** */
    //**************************************************************************** */
    Route::get('/current-taxi-movement', [TaxiMovementController::class, 'currentTaxiMovement'])->name('current.taxi.movement');
    //*************************** End route taxi-movement *********************************** */

});
Route::get('/drivers', [DriversController::class, 'index']);



require __DIR__ . '/auth.php';
