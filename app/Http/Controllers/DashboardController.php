<?php

namespace App\Http\Controllers;

use App\Enums\UserEnums\UserType;
use App\Models\Calculation;
use App\Models\Taxi;
use App\Models\TaxiMovement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Return company information page
     * @return JsonResponse
     */
    public function index()
    {

        $totalDrivers = User::role(UserType::TaxiDriver()->key)->count();
        $totalTaxi = Taxi::count();
        $calculations = Calculation::where('is_bring', true)->sum('totalPrice');
        $requests = TaxiMovement::where('is_completed', true)->count();
        $lifeTaxiMovements = TaxiMovement::getLifeTaxiMovements() ?? null;
        $readyDrivers = User::getReadyDrivers() ?? null;

        $data = [
            'totalDrivers' => $totalDrivers,
            'totalTaxi' => $totalTaxi,
            'calculations' => $calculations,
            'requests' => $requests,
            'lifeTaxiMovements' => $lifeTaxiMovements,
            'readyDrivers' => $readyDrivers
        ];

        return api_response(data: $data, message: 'Successfully get data');
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
     * @return JsonResponse
     */
    public function viewReport()
    {
        $data = User::getReportData(Carbon::today(), Carbon::tomorrow()->subSecond());
        return api_response(data: $data, message: 'Successfully get data');
    }

    /**
     * Generate and download a PDF report.
     * @return \Illuminate\Http\Response
     */
    public function downloadReport()
    {
        $data = User::getReportData(Carbon::today(), Carbon::tomorrow()->subSecond());

        $pdf = Pdf::loadView('Report.report', $data);

        return $pdf->download('daily_report.pdf');
    }
}
