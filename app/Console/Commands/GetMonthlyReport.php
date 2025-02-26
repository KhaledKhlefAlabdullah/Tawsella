<?php

namespace App\Console\Commands;

use App\Models\User;
use \Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-monthly-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and store the monthly report as a PDF file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Define the start and end of the week using Carbon
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            $admin = User::find(getAdminId());
            send_notifications($admin, 'التقرير الشهري');

            User::downloadReport($startDate, $endDate, 'report.monthly-report');

            $this->info('Monthly report has been generated and saved as ');

        } catch (\Exception $e) {
            // Log any errors encountered during execution
            Log::error('Error generating monthly report: ' . [$e->getMessage()]);
            $this->error('Failed to generate the monthly report. Check logs for details.');
        }
    }
}
