<?php

namespace App\Console\Commands;

use App\Enums\UserEnums\UserType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredDrivers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deactivate-expired-drivers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate drivers whose activation has expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Fetch drivers with expired activations
            $now = Carbon::now();

            $drivers = User::whereNot('user_type', UserType::Customer())
                ->whereDate('activation_expired_date', '<=', $now)
                ->where('active', true) // Assuming there is an 'active' column to check if they are currently active
                ->get();

            $message = 'Your activation has expired. Please renew it as soon as possible.';
            foreach ($drivers as $driver) {
                // Deactivate the driver
                $driver->update(['active' => false]);
                driverExpiredNotification($driver, $message, ['mail']);

                $this->info('Deactivated driver with ID: ' . $driver->id);
            }

            $this->info('Expired drivers deactivated successfully.');
        } catch (\Exception $e) {
            Log::error('Error deactivating expired drivers: ' . $e->getMessage());
            $this->error('Failed to deactivate expired drivers. Check logs for details.');
        }
    }
}
