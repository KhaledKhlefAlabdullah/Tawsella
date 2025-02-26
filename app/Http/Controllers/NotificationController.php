<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $unreadNotifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->select('id','data')->get();

        return api_response(data: $unreadNotifications, message: 'تم جلب الاشعارات بنجاح');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unReadNotifications(Request $request)
    {
        $unreadNotifications = Auth::user()->unReadNotifications()->orderBy('created_at', 'desc')->select('id','data')->get();

        return api_response(data: $unreadNotifications, message: 'تم جلب الاشعارات غير المقروءة ينجاح');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function readNotifications()
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return api_response(message: 'تم تعليم جميع الاشعارات كمقروءة');
        } catch (\Exception $e) {
            return api_response(message: 'فشل في تعليم جميع الاشارات كمقروءة', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * @param string $notificationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function readSingleNotifications(string $notificationId)
    {
        try {
            $notification = auth()->user()->notifications()->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
                return api_response(message: 'تم تعليم الاشعار كمقروء');
            }
            return api_response(message: 'الإشعار غير موجود', code: 404, errors: ['الأشعار غير موجود']);
        } catch (\Exception $e) {
            return api_response(message: 'فشل في تعليم الإشعار كمقروء', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * @param string $notificationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSingleNotifications(string $notificationId){
        try {
            $notification = auth()->user()->notifications()->find($notificationId);
            if ($notification) {
                $notification->delete();
            }
            return api_response(message: 'تم حذف الإشعار بنجاح');
        }catch (\Exception $e){
            return api_response(message: 'هناك خطأ في حذف الإشعار', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotifications()
    {
        try {
            Auth::user()->notifications()->delete();
            return api_response(message: 'تم حذف جميع الإشعارات بنجاح');
        } catch (\Exception $e) {
            return api_response(message: 'فشل في حذف الإشعارات', code: 500, errors: [$e->getMessage()]);
        }
    }
}
