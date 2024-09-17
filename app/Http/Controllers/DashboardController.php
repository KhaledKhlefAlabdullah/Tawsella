<?php

namespace App\Http\Controllers;

use App\Models\TaxiMovement;
use App\Models\User;
use Exception;

class DashboardController extends Controller
{

    public function index()
    {

        return view('dashboard');
    }
}
