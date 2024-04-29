<?php

namespace App\Http\Controllers;

use App\Models\TaxiMovement;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try{

            $currentDate = Carbon::now()->toDateString();

            // Query to get requests for the current day
            $taxiMovement = TaxiMovement::select(
                'taxi_movements.id',
                'taxi_movements.my_address as from',
                'taxi_movements.destnation_address as to',
                'taxi_movements.gender',
                'taxi_movements.start_latitude as lat',
                'taxi_movements.start_longitude as long',
                'customer_profile.avatar as avatar',
                'customer_profile.name as customer_name',
                'customer_profile.phoneNumber as customer_phone',
                'taxi_movement_types.type',
                'taxi_movements.created_at as time'
                )
                ->leftJoin('users as customer','taxi_movements.customer_id','=','customer.id')
                ->leftJoin('user_profiles as customer_profile','taxi_movements.customer_id','=','customer_profile.user_id')
                ->leftJoin('taxi_movement_types','taxi_movements.movement_type_id','=','taxi_movement_types.id')
                ->whereDate('taxi_movements.created_at', $currentDate)
                ->where(['taxi_movements.is_don' => false,'taxi_movements.request_state' => 'pending'])
                ->get();

            $drivers = getReadyDrivers();


            return view('dashboard',['lifeTaxiMovements' => $taxiMovement, 'drivers' => $drivers]);
        }
        catch(Exception $e){
            return redirect()->back()->withErrors('هناك خطأ في الحصول على حركة سيارات الأجرة الحالية'.$e->getMessage())->withInput();
        }
    }
}
