<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Enums\UserEnums\UserType;
use App\Models\Calculation;
use App\Models\Taxi;
use App\Models\TaxiMovement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardComposer
{
    public function compose(View $view)
    {
        $data = [];

        $user = Auth::user();
        if ($user && $user->user_type == UserType::Admin) {
            $data['totalDrivers'] = User::where('user_type', UserType::TaxiDriver)->count();
        }

        // Query to get requests for the current day

        $data['totalTaxi'] = Taxi::count();
        $data['calculations'] = Calculation::where('is_bring', true)->sum('totalPrice');
        $data['requests'] = TaxiMovement::where('is_completed', true)->count();
        $data['lifeTaxiMovements'] = TaxiMovement::getLifeTaxiMovements() ?? null;
        $data['drivers'] = User::getReadyDrivers() ?? null;
        $view->with($data);
    }
}
