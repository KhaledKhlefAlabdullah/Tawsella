<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AboutUsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctom')->group(function () {

    Route::middleware('driver')->group(function () {

        Route::group(['prefix' => 'driver'], function () {

        });
    });

    Route::middleware('customer')->group(function () {

        Route::group(['prefix' => 'customer'], function () {

        });
    });
});


Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'aboutus'], function () {
        Route::get('/', [AboutUsController::class, 'index']); // عرض قائمة السجلات
        Route::get('/{aboutUs}', [AboutUsController::class, 'show']); // عرض سجل محدد
        Route::post('/', [AboutUsController::class, 'store']); // إنشاء سجل جديد
        Route::put('/{aboutUs}', [AboutUsController::class, 'update']); // تحديث سجل محدد
        Route::delete('/{aboutUs}', [AboutUsController::class, 'destroy']); // حذف سجل محدد
    });
});


require __DIR__ . '/auth.php';
