<?php

namespace App\Models\Traits\UserTraits;

use App\Enums\UserEnums\UserType;
use App\Models\Movement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait CustomerTait
{
    public static function mappingNearestDrivers($drivers)
    {
        return $drivers->map(function ($driver) {
            // Basic user mapping
            return [
                'user_id' => $driver->id,
                'name' => $driver->profile->name ?? '',
                'email' => $driver->email,
                'phone_number' => $driver->profile->phone_number ?? '',
                'avatar' => $driver->profile->avatar ?? '',
                'user_type' => UserType::getKey($driver->user_type),
                'gender' => $driver->profile->gender ?? '',
                'distance' => $driver->distance.' KM',
                'latitude' => $driver->last_location_latitude,
                'longitude' => $driver->last_location_longitude,
            ];
        }
        );
    }

    // Method to find users near a given point
    public static function scopeNearLocation(Builder $query, float $latitude, float $longitude, float $radius = 10): Builder
    {
        return $query->selectRaw(
            "*, (6371 * acos(cos(radians(?)) * cos(radians(last_location_latitude)) *
            cos(radians(last_location_longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(last_location_latitude)))) AS distance",
            [$latitude, $longitude, $latitude]
        )->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc');
    }

    public static function checkExistingCustomerMovements(string $customer_id){
        // To check if the customer have request in last 4 mentees don't create new one and return message
        $existsRequest = Movement::where('customer_id', $customer_id)
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->latest()
            ->first();

        if ($existsRequest) {
            return api_response(
                message: 'You have recently requested a car. Please wait a moment while your request is being processed.',
                code: 429
            );
        }
    }
}
