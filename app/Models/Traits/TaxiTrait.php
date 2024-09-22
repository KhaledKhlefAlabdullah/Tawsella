<?php

namespace App\Models\Traits;

use App\Models\Taxi;

trait TaxiTrait
{
    /**
     * @param $taxis
     * @return mixed
     */
    public static function mappingTaxis($taxis){

        return $taxis->map(function($taxi){
            $driverProfile = $taxi->driver->profile;
            return (object)[
                'driverName' => $driverProfile->name,
                'driverAvatar' => $driverProfile->avatar,
                'id' => $taxi->id,
                'car_name' => $taxi->car_name,
                'lamp_number' => $taxi->lamp_number,
                'plate_number' => $taxi->plate_number
            ];
        });
    }
}
