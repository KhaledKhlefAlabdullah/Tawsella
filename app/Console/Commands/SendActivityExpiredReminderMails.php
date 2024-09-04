<?php

namespace App\Console\Commands;

use App\Enums\UserType;
use App\Models\User;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendActivityExpiredReminderMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:activation-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send activation reminders to users with expired activations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $fiveDaysFromNow = Carbon::now()->addDays(5)->startOfDay();
            $now = Carbon::now()->startOfDay();

            $drivers = User::whereNot('user_type', UserType::CUSTOMER())
                ->whereBetween('activation_expired_date', [$now, $fiveDaysFromNow])
                ->where('active', true) // Ensure we are only deactivating active drivers
                ->paginate(100);

            foreach ($drivers as $driver) {
                $message = $this->generateMessage($driver->activation_expired_date);
                driverExpiredNotification($driver, $message, ['database','mail']);
            }

            $this->info('Activation reminders sent successfully.');
        } catch (\Exception $e) {
            Log::error('Error sending activation reminders: ' . $e->getMessage());
            $this->error('Failed to send activation reminders. Check logs for details.');
        }
    }

    /**
     * Generate notification message for every user.
     *
     * @param Date $expirationDate
     */
    private function generateMessage($expirationDate) {
        $daysToExpired = Carbon::parse($expirationDate)->diffInDays(Carbon::now());
        return 'Your activation will expire in ' . $daysToExpired . ' days. Please renew it before it expires.';
    }
}
