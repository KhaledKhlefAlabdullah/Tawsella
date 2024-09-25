<?php

namespace App\Models\Traits\UserTraits;

use App\Enums\UserEnums\DriverState;
use App\Enums\UserEnums\UserType;
use App\Models\TaxiMovement;
use App\Models\User;
use BenSampo\Enum\Exceptions\InvalidEnumMemberException;

trait DriverTrait
{
    /**
     * Processed movement state
     * @param TaxiMovement $taxiMovement
     * @param User $driver
     * @param int $state
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse|void
     */
    public static function processMovementState(TaxiMovement $taxiMovement, int $state, string $message = null, User $driver = null)
    {
        if ($taxiMovement->is_canceled) {
            return api_response(
                message: 'The request has already been canceled by the customer. We apologize for any inconvenience caused.',
                code: 410);
        } else {
            // Update the request state
            $taxiMovement->update([
                'request_state' => $state,
                'state_message' => $message,
                'driver_id' => $driver->id ?? null,
                'taxi_id' => $driver->taxi->id ?? null
            ]);
        }
    }

    /**
     * Get all ready drivers
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|never
     */
    public static function getReadyDrivers()
    {
        $drivers = User::with(['taxi', 'profile'])
            ->where([
                'driver_state' => DriverState::Ready,
                'is_active' => true
            ])
            ->has('taxi') // Ensure the user has a related taxi
            ->get();

        $mappedDrivers = $drivers->map(function ($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->profile->name,
                'gender' => $driver->profile->gender,
                'avatar' => $driver->profile->avatar
            ];
        });

        return $mappedDrivers;
    }

    /**
     * Get all drivers in the database with pagination.
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getDrivers($perPage = 15)
    {
        $drivers = User::with(['taxi', 'profile'])
            ->role(UserType::TaxiDriver()->key)
            ->paginate($perPage); // Use paginate instead of get()

        // Map the drivers data using the mapping method
        $mappedDrivers = self::mappingDrivers($drivers);

        // Return paginated mapped drivers
        return $mappedDrivers;
    }


    /**
     * Mapping the drivers.
     * @param $drivers
     * @throws InvalidEnumMemberException
     */
    public static function mappingDrivers($drivers)
    {
        // Extract the items and map the driver data
        $mappedDrivers = $drivers->getCollection()->map(function ($driver) {
            return self::extracted($driver);
        });

        // Replace the items in the paginator with the mapped items
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $mappedDrivers, // Mapped items
            $drivers->total(), // Total items
            $drivers->perPage(), // Items per page
            $drivers->currentPage(), // Current page
            ['path' => request()->url()] // Path for pagination links
        );
    }

    /**
     * Mapping a single driver
     * @param User $driver
     * @return mixed
     */
    public static function mappingSingleDriver(User $driver)
    {
        return self::extracted($driver);
    }

    /**
     * Get drivers dont have taxis
     * @return mixed
     */
    public static function getDriversDontHaveTaxi()
    {
        $drivers = User::role(UserType::TaxiDriver()->key)
            ->where('is_active', true)
            ->whereDoesntHave('taxi')
            ->with('profile:id,user_id,name,avatar')
            ->get();

        return $drivers;
    }

    /**
     * @param $driver
     * @return object
     * @throws \BenSampo\Enum\Exceptions\InvalidEnumMemberException
     */
    public static function extracted($driver): object
    {
        $unBring = $driver->calculations()->where('is_bring', false)->sum('totalPrice');
        return (object)[
            'driver_id' => $driver->id,
            'name' => $driver->profile->name,
            'email' => $driver->email,
            'phone_number' => $driver->profile->phone_number,
            'avatar' => $driver->profile->avatar,
            'is_active' => $driver->is_active,
            'unBring' => $unBring,
            'driver_state' => DriverState::getKey($driver->driver_state),
            'plate_number' => $driver->taxi?->plate_number,
            'lamp_number' => $driver->taxi?->lamp_number,
        ];
    }
}
