<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;

if(!function_exists('driverExpiredNotification')){
    /**
     * Send notifications to a driver.
     *
     * @param User $user
     * @param string $message
     * @param array $channels
     */
    function driverExpiredNotification(User $driver, string $message, array $channels = ['database'])
    {
        try {
            send_notifications($driver, $message, $channels);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Notification error for user ' . $driver->id . ': ' . $e->getMessage());

            try {
                // Attempt to get the admin user
                $admin = getAndCheckModelById(User::class, getAdminId());

                // Compose the error message to notify the admin
                $adminMessage = "Failed to send activation notification to: \n" .
                    "User: " . $driver->profile->name . "\n" .
                    "Email: " . $driver->email;

                // Send error notification to the admin
                send_notifications($admin, $adminMessage, ['mail']);
            } catch (\Exception $innerException) {
                Log::error('Failed to notify admin about the notification error: ' . $innerException->getMessage());
            }
        }
    }
}
