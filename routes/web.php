<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxiMovementTypeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\AboutUsController;

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

Route::resource('aboutus', AboutUsController::class);
Route::resource('offers', OfferController::class);

Route::resource('taxi_movement_types', TaxiMovementTypeController::class);

Route::middleware('auth')->group(function () {

    Route::middleware('admin')->group(function () {

        Route::get('/', function () {
            return view('admin.dashboard');
        });

        Route::resource('aboutus', AboutUsController::class);
        Route::resource('offers', OfferController::class);

        Route::resource('taxi_movement_types', TaxiMovementTypeController::class);

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // For create the drivers acounts
        Route::post('/register_admin', [RegisteredUserController::class, 'admin_store'])->name('register_admin');

    });
});

require __DIR__ . '/auth.php';
