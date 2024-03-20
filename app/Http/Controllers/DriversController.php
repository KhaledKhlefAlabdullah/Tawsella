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
            ->join('user_profiles','users.id','=','user_profiles.user_id')
            ->join('taxis','users.id','=','taxis.driver_id')
            ->where('users.user_type','driver')->get();
            
            return view('',['drivers' => $drivers]);
        }
        catch(Exception $e){
            return abort(500,'there error in getting the drivers data');
        }
    }
    
}
