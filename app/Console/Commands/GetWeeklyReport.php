<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetWeeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-weekly-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and store the weekly report as a PDF file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Define the start and end of the week using Carbon
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();

            User::downloadReport($startDate, $endDate, 'report.weekly-report');

            $this->info('Weekly report has been generated and saved as ');

        } catch (\Exception $e) {
            // Log any errors encountered during execution
            Log::error('Error generating weekly report: ' . $e->getMessage());
            $this->error('Failed to generate the weekly report. Check logs for details.');
        }
    }

}
