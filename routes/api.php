<?php

use App\Events\test;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\EnsureEmailIsVerifiedByCodeMiddleware;

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

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::middleware(['auth:sanctum', EnsureEmailIsVerifiedByCodeMiddleware::class])->group(function () {
    require __DIR__.'/Roles/driver.php';
    require __DIR__.'/Roles/customer.php';
    require __DIR__ . '/Roles/admin.php';
    require __DIR__.'/Roles/allAuthUsers.php';
});

Route::post('test', function () {
    event(new test('Hello, world!'));
    return 'Event dispatched!';
});
require __DIR__.'/Roles/publicApis.php';
require __DIR__ . '/auth.php';

