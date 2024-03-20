<?php

use App\Events\CreateTaxiMovementEvent;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
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

    Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');

    Route::get('/create-driver', [RegisteredUserController::class, 'create'])
        ->name('create-driver');

    // For create the drivers acounts
    Route::post('/store-driver', [RegisteredUserController::class, 'admin_store'])->name('store-driver');
});

Route::get("/test", function () {
    return event(new CreateTaxiMovementEvent("2", 223, 3344));
});

require __DIR__ . '/auth.php';
