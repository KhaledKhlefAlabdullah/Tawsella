<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaxiController;
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
Route::get('/serve',[TaxiMovementTypeController::class,'index']);
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
    Route::get('/dashboard', function () {
        return view('dashboard', []);
    })->middleware(['auth', 'verified'])->name('dashboard');
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
        ->name('create-driver');
    // For create the drivers acounts
    Route::post('/store-driver', [RegisteredUserController::class, 'admin_store'])->name('store-driver');
    Route::group(['prefix' => 'drivers'], function () {
        Route::get('/', [DriversController::class, 'index']);
    });
    //***************************End route driver ******************************** */
    //**************************************************************************** */

    //***************************start route Taxi ******************************** */
    Route::get('/taxi', [TaxiController::class, 'index'])->name('taxis.index');
    Route::get('/taxis/create', [TaxiController::class, 'create'])->name('taxis.create');
    Route::post('/taxis', [TaxiController::class, 'store'])->name('taxis.store');
    //*************************** End route Taxi *********************************** */
    //**************************************************************************** */

    //*************************** Start route offers *********************************** */
    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    //*************************** End route offers *********************************** */
    //**************************************************************************** */

    Route::post('/accept_reject_request',[TaxiMovementTypeController::class])->name('accept_reject_request');

    

});
Route::get('/drivers', [DriversController::class, 'index']);



require __DIR__ . '/auth.php';
