<?php

use App\Events\CreateTaxiMovementEvent;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaxiController;
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
    return view('welcome');
});



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/profiles', function () {
        return view('profile.profile');
    });
    Route::get('/Contact', function () {
        return view('Contact');
    });
    Route::get('/payment', function () {
        return view('payment');
    });
    Route::get('/dashboard', function () {
        return view('dashboard', []);
    })->middleware(['auth', 'verified'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/create-driver', [RegisteredUserController::class, 'create'])
        ->name('create-driver');

    // For create the drivers acounts
    Route::post('/store-driver', [RegisteredUserController::class, 'admin_store'])->name('store-driver');

    //***************************start route Taxi ******************************** */
    Route::get('/taxi', [TaxiController::class, 'index'])->name('taxis.index');
    Route::get('/taxis/create', [TaxiController::class, 'create'])->name('taxis.create');
    Route::post('/taxis', [TaxiController::class, 'store'])->name('taxis.store');

    //*************************** End route Taxi *********************************** */

    Route::group(['prefix' => 'drivers'], function () {
        Route::get('/', [DriversController::class, 'index']);
    });
});
Route::get('/drivers', [DriversController::class, 'index']);

Route::get("/test", function () {
    return event(new CreateTaxiMovementEvent("2", 223, 3344));
});

require __DIR__ . '/auth.php';
