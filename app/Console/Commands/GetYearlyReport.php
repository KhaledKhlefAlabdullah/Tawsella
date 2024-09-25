<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GetYearlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-yearly-report';

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
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();

            User::downloadReport($startDate, $endDate, 'report.yearly-report');

            $this->info('Yearly report has been generated and saved as ');

        } catch (\Exception $e) {
            // Log any errors encountered during execution
            Log::error('Error generating yearly report: ' . [$e->getMessage()]);
            $this->error('Failed to generate the yearly report. Check logs for details.');
        }
    }

}
