<?php

namespace App\Models\Traits;

use App\Models\Taxi;

trait TaxiTrait
{
    /**
     * @param $taxis
     * @return mixed
     */
    public static function mappingTaxis($taxis)
    {
        return collect($taxis)->map(function ($taxi) {
            // Retrieve the driver model associated with the taxi
            $driver = $taxi->driver;
            // Check if the driver and profile are loaded
            $driverProfile = $driver ? $driver->profile : null;
            return (object)[
                'driverName' => $driverProfile ? $driverProfile->name : 'N/A', // Fallback to 'N/A' if profile is not found
                'driverAvatar' => $driverProfile ? $driverProfile->avatar : null, // Fallback to null if profile is not found
                'id' => $taxi->id,
                'car_name' => $taxi->car_name,
                'lamp_number' => $taxi->lamp_number,
                'plate_number' => $taxi->plate_number
            ];
        });
    }
}
