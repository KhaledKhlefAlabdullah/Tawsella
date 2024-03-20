<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class DriversController extends Controller
{
    public function index(){
        try{
            $drivers = User::select('user_profiles.name','user_profiles.phoneNumber','users.email','taxis.plate_number')
            ->leftJoin('user_profiles','users.id','=','user_profiles.user_id')
            ->leftJoin('taxis','users.id','=','taxis.driver_id')
            ->where('users.user_type','driver')->get();
            return view('Driver.index',['drivers' => $drivers]);

        }
        catch(Exception $e){
            return abort(500,'there error in getting the drivers data');
        }
    }

}
