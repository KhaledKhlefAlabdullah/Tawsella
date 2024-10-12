<?php

namespace App\Models\Traits\UserTraits;

use App\Enums\MovementRequestStatus;
use App\Models\Calculation;
use App\Models\TaxiMovement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

trait AdminTrait
{
    /**
     * @return array
     */
    public static function getReportData(Carbon $startDate, Carbon $endDate)
    {
        // Movements within the date range
        $movementsQuery = TaxiMovement::whereBetween('created_at', [$startDate, $endDate]);

        $numberOfMovements = $movementsQuery->count() ?? 0;
        $numberOfCompletedMovements = $movementsQuery->where('is_completed', true)->count() ?? 0;
        $numberOfRejectedMovements = $movementsQuery->where('request_state', MovementRequestStatus::Rejected)->count() ?? 0;
        $numberOfCanceledMovements = $movementsQuery->where('is_canceled', true)->count() ?? 0;

        // Total amount of movements within the date range
        $totalAmount = Calculation::whereBetween('created_at', [$startDate, $endDate])->sum('totalPrice') ?? 0;

        // Get driver movements with related data within the date range using eager loading
        $drivers = User::whereHas('driver_movements', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->with(['driver_movements' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }, 'profile'])
            ->get() ?? null;

        $driversMovements = [];
        foreach ($drivers as $driver) {
            $movementsCount = $driver->driver_movements->count();
            $totalDriverAmount = Calculation::whereBetween('created_at', [$startDate, $endDate])
                ->where('driver_id', $driver->id)
                ->sum('totalPrice') ?? 0;

            $driversMovements[] = [
                'avatar' => $driver->profile->avatar,
                'name' => $driver->profile->name,
                'movementsCount' => $movementsCount,
                'totalAmount' => $totalDriverAmount
            ];
        }

        return [
            'numberOfMovements' => $numberOfMovements,
            'numberOfCompletedMovements' => $numberOfCompletedMovements,
            'numberOfRejectedMovements' => $numberOfRejectedMovements,
            'numberOfCanceledMovements' => $numberOfCanceledMovements,
            'totalAmount' => $totalAmount,
            'driversMovements' => $driversMovements,
        ];
    }


    /**
     * Download the report
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param string $message
     * @throws \Exception
     */
    public static function downloadReport(Carbon $startDate, Carbon $endDate, string $message)
    {
        // Fetch the report data for the week
        $data = User::getReportData($startDate, $endDate);

        // Load the view and pass the data to generate a PDF
        $pdf = Pdf::loadView('Report.report', $data);

        // Define the path where the PDF will be saved
        $fileName = 'weekly_report_' . Carbon::now()->format('Y_m_d') . '.pdf';

        $admin = getAndCheckModelById(User::class, getAdminId());
        send_notifications($admin, __($message));

        return $pdf->download($fileName);
    }
}
