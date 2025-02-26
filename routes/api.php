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
        'edphC3L4SbqyzA2IxMf60l:APA91bG7NHDd78D70aBGLUNzMDqQXmlB69a2BjFsC5V9DOH2gX0a5_DR0HfbCihXWRfxqiWT85hd-QYBpo1PMd_uutoZ_rDER_zlkJHV_Fw-UQV3wtOEfjk'
    );
});
require __DIR__.'/Roles/publicApis.php';
require __DIR__ . '/auth.php';

