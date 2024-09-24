<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $data = User::getReportData(Carbon::today(), Carbon::tomorrow()->subSecond());

        return view('Report.report', $data);
    }

    /**
     * Generate and download a PDF report.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadReport()
    {
        $data = User::getReportData(Carbon::today(), Carbon::tomorrow()->subSecond());

        $pdf = Pdf::loadView('Report.report', $data);

        return $pdf->download('daily_report.pdf');
    }


}
