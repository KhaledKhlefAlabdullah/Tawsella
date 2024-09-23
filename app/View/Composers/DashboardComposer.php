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
        if ($user && in_array(UserType::Admin()->key, $user->getRoleNames()->toArray())) {
            $data['totalDrivers'] = User::role(UserType::TaxiDriver()->key)->count();
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
