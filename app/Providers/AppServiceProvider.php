<?php

namespace App\Providers;

use App\Models\Calculation;
use App\Models\Taxi;
use App\Models\TaxiMovement;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
            $data['calculations'] = Calculation::where('is_bring',true)->sum('totalPrice');
            $data['requests'] = TaxiMovement::where('is_completed',true)->count();
            $view->with($data);
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
