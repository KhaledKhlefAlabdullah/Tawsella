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
        $unreadNotifications = Auth::user()->notifications;

        return api_response(data: $unreadNotifications, message: "Successfully get notifications");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unReadNotifications(Request $request)
    {
        $unreadNotifications = Auth::user()->unReadNotifications;

        return api_response(data: $unreadNotifications, message: "Successfully get unread notifications");
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function readNotifications()
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return api_response(message: "Successfully read notifications");
        } catch (\Exception $e) {
            return api_response(errors: [$e->getMessage()], message: "Failed to read notifications", code: 500);
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
                return api_response(message: "Successfully read notification");
            }
            return api_response(errors: ["Notification not found"], message: "Notification not found", code: 404);
        } catch (\Exception $e) {
            return api_response(errors: [$e->getMessage()], message: "Failed to read notification", code: 500);
        }
    }
}
