<?php


use App\Http\Controllers\DashboardController;
use App\Models\User;
use App\Notifications\StarTaxiNotification;
use Illuminate\Support\Facades\Auth;
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

Route::group(['controller' => DashboardController::class], function () {
    Route::get('/', function (){
        return view('welcome');
    })->name('welcome');

    Route::get('/AppPlatform', 'appPlatform')->name('AppPlatform');
});
