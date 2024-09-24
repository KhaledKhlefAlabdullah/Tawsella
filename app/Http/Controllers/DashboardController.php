<?php

namespace App\Http\Controllers;

use App\Enums\MovementRequestStatus;
use App\Models\Calculation;
use App\Models\TaxiMovement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Return company information page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Return install app page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function appPlatform()
    {
        return view('ApplicationPlatform');
    }

    /**
     * Generate report about work
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function viewReport()
    {
        $data = $this->getReportData();

        return view('Report', $data);
    }

    /**
     * Generate and download a PDF report.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadReport()
    {
        $data = $this->getReportData();

        $pdf = Pdf::loadView('Report', $data);

        return $pdf->download('daily_report.pdf');
    }

    /**
     * @return array
     */
    protected function getReportData()
    {
        $today = Carbon::today();

        $numberOfMovementsToday = TaxiMovement::whereDate('created_at', $today)->count() ?? 0;

        $numberOfCompletedMovementsToday = TaxiMovement::whereDate('created_at', $today)->where('is_completed', true)->count() ?? 0;

        $numberOfRejectedMovementsToday = TaxiMovement::whereDate('created_at', $today)->where('request_state', MovementRequestStatus::Rejected)->count() ?? 0;

        $numberOfCanceledMovementsToday = TaxiMovement::whereDate('created_at', $today)->where('is_canceled', true)->count() ?? 0;

        $totalAmount = Calculation::whereDate('created_at', $today)->sum('totalPrice') ?? 0;

        $movementsToday = TaxiMovement::with(['calculations', 'driver', 'movement_type'])->whereDate('created_at', $today)->get() ?? 0;

        $driversMovements = User::with(['driver_movements.calculations', 'driver_movements.movement_type', 'driver_ratings'])->whereDate('created_at', $today)->get() ?? 0;

        return [
            'numberOfMovementsToday' => $numberOfMovementsToday,
            'numberOfCompletedMovementsToday' => $numberOfCompletedMovementsToday,
            'numberOfRejectedMovementsToday' => $numberOfRejectedMovementsToday,
            'numberOfCanceledMovementsToday' => $numberOfCanceledMovementsToday,
            'totalAmount' => $totalAmount,
            'movementsToday' => $movementsToday,
            'driversMovements' => $driversMovements
        ];
    }
}
