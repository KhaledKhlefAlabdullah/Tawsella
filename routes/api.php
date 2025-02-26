<?php

use App\Events\test;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

// to verify the email use this 'verified.email' in middleware
// Also check the user model
Route::middleware(['auth:sanctum'])->group(function () {
    require __DIR__.'/Roles/driver.php';
    require __DIR__.'/Roles/customer.php';
    require __DIR__ . '/Roles/admin.php';
    require __DIR__.'/Roles/allAuthUsers.php';
});

Route::post('test', function () {
    $admin = User::find(getAdminId());
    $fcm = new \App\Services\FcmNotificationService();
    $customerPayload = [
        'notification' => [
            'title' => 'تم قبول طلبك!',
            'body' => 'الطلب الذي أرسلته منذ قليل تم قبوله بنجاح.'
        ]
    ];
    return $fcm->sendNotification($customerPayload,
        'di7t1hazRVqxUt7rWHJvTD:APA91bEqUeTc9BHwJyIHlRImEWXWv_fsv84gts6tKD-9bNxa6L-9v1XZ7hxFZuZ8qUWaBHvGS8-XAbz53YPgpkdmWIC7mJ97zr4MOV7EMFq2LmKf2j13Cw8'
    );
});
require __DIR__.'/Roles/publicApis.php';
require __DIR__ . '/auth.php';

