<?php

namespace App\Http\Controllers;

use App\Models\TaxiMovement;
use App\Models\User;
use Exception;

class DashboardController extends Controller
{

    public function index()
    {
        try{

            // Query to get requests for the current day
            $taxiMovement = TaxiMovement::getTaxiMovementsForToday();

            $drivers = User::getReadyDrivers();

            return view('dashboard',['lifeTaxiMovements' => $taxiMovement, 'drivers' => $drivers]);
        }
        catch(Exception $e){
            return redirect()->back()->withErrors('Error in getting today live movements\n errors:'.$e->getMessage())->withInput();
        }
    }
}
