<?php

namespace App\Models\Traits;

use App\Models\Calculation;
use App\Models\TaxiMovement;
use App\Models\User;
use Illuminate\Support\Carbon;
use Ramsey\Collection\Collection;

trait CalculationTrait
{
    public static function mappingDriversCalculations($drivers)
    {
        return collect($drivers)->map(function ($driver) {
            $combinedAccounts = [];

            $driver_id = $driver->id;
            $total_today = Calculation::todayAccounts($driver_id);
            $total_previous = Calculation::totalAccounts($driver_id);

            return (object)[
                'driver_id' => $driver_id,
                'name' => $driver->profile?->name,
                'avatar' => $driver->profile?->avatar,
                'plate_number' => $driver->taxi?->plate_number,
                'today_account' => $total_today,
                'all_account' => $total_previous
            ];
        });

    }

    /**
     * Calculate all the amounts received today by the driver but not yet delivered.
     * @return float
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public static function todayAccounts(string $driver_id)
    {
        // Get today's date
        $today = Carbon::now()->toDateString();

        $todayAccounts = Calculation::where('driver_id', $driver_id)
            ->where('is_bring', false)
            ->whereDate('created_at', $today)
            ->sum('totalPrice');

        return $todayAccounts ?? 0;
    }

    /**
     * Calculate all the amounts received by the driver but not yet delivered.
     * @return float
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public static function totalAccounts(string $driver_id)
    {
        $totalAccounts = Calculation::where('driver_id', $driver_id)
            ->where('is_bring', false)
            ->sum('totalPrice');

        return $totalAccounts ?? 0;
    }

    /**
     * Get driver movements with calculations
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     * @return Collection of data to view in page
     */
    public static function driverMovementsCalculations(string $driver_id)
    {
        $driverMovements = TaxiMovement::with(['calculations' => function ($query) {
            $query->where('is_bring', false);
        }])
            ->where('driver_id', $driver_id)
            ->where('is_completed', true)
            ->get();

        return $driverMovements->map(function ($movement) {
            $calculation = $movement->calculations->first();
            return [
                'saddress' => $movement->start_address,
                'eaddress' => $movement->destination_address,
                'slat' => $movement->start_latitude,
                'along' => $movement->start_longitude,
                'elat' => $movement->end_latitude,
                'elong' => $movement->end_longitude,
                'date' => $movement->created_at,
                'totalPrice' => $calculation ? $calculation->totalPrice : 0,
                'distance' => $calculation ? $calculation->distance : null,
            ];
        });
    }


}
