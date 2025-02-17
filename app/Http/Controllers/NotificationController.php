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

        return api_response(data: $unreadNotifications, message: 'Successfully get notifications');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unReadNotifications(Request $request)
    {
        $unreadNotifications = Auth::user()->unReadNotifications()->orderBy('created_at', 'desc')->select('id','data')->get();

        return api_response(data: $unreadNotifications, message: 'Successfully get unread notifications');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function readNotifications()
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return api_response(message: 'Successfully read notifications');
        } catch (\Exception $e) {
            return api_response(message: 'Failed to read notifications', code: 500, errors: [$e->getMessage()]);
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
                return api_response(message: 'Successfully read notification');
            }
            return api_response(message: 'Notification not found', code: 404, errors: ['Notification not found']);
        } catch (\Exception $e) {
            return api_response(message: 'Failed to read notification', code: 500, errors: [$e->getMessage()]);
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
            return api_response(message: 'Notification deleted');
        }catch (\Exception $e){
            return api_response(message: 'Deleting notification error', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotifications()
    {
        try {
            Auth::user()->notifications()->delete();
            return api_response(message: 'Successfully delete all notifications');
        } catch (\Exception $e) {
            return api_response(message: 'Failed to delete all notifications', code: 500, errors: [$e->getMessage()]);
        }
    }
}
