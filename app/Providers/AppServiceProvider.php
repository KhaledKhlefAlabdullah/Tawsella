<?php

namespace App\Providers;

use App\Models\Calculations;
use App\Models\Taxi;
use App\Models\TaxiMovement;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->definePolicies();

        view()->composer('*', function ($view) {
            $data = [];

            $user = Auth::user();
            if ($user && $user->user_type === 'admin') {
                $data['totalDrivers'] = User::where('user_type', 'driver')->count();
            }
            $data['totalTaxi'] = Taxi::count();
            $data['calcolations'] = Calculations::where('is_bring',true)->sum('totalPrice');
            $data['requests'] = TaxiMovement::where('is_completed',true)->count();
            $view->with($data);
        });
    }

    protected function definePolicies()
    {
    }
}

