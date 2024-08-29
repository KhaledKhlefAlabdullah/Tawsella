<?php

namespace App\Models\Traits;

use App\Enums\UserType;
use App\Models\Movement;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Builder;

trait UserTrait
{
    public static function mappingUsers($users)
    {
        return $users->map(function ($user) {
            // Basic user mapping
            $mapping = [
                'user_id' => $user->id,
                'name' => $user->profile->name ?? '',
                'points' => $user->points ?? 0,
                'email' => $user->email,
                'phone_number' => $user->profile->phone_number ?? '',
                'avatar' => $user->profile->avatar ?? '',
                'is_active' => $user->is_active,
                'user_type' => UserType::getKey($user->user_type),
                'gender' => $user->profile->gender ?? '',
                'created_at' => $user->created_at,
            ];

            // Movement counts (customer or driver)
            if ($user->relationLoaded('customer_movements') || $user->relationLoaded('driver_movements')) {
                $canceledMovements = count_items(Movement::class, ['customer_id' => $user->id, 'is_canceled' => true]);
                $completedMovements = count_items(Movement::class, ['customer_id' => $user->id, 'is_completed' => true]);
                $mapping = array_merge($mapping, ['completed_movements' => $completedMovements, 'canceled_movements' => $canceledMovements]);
            }

            // Additional driver-related information
            if (!in_array($user->user_type, [UserType::CUSTOMER, UserType::ADMIN])) {
                $ratingsNum = count_items(Rating::class, ['driver_id' => $user->id]);
                $userRating = $user->rating;
                $rating = $userRating ? $userRating->sum('rating') / $ratingsNum : null;

                $vehicle = $user->vehicle;

                $mapping = array_merge($mapping, [
                    'ratings_number' => $ratingsNum,
                    'rating' => $rating,
                    'plat_number' => $vehicle->plat_number ?? '',
                    'vehicle_image' => $vehicle->vehicle_image ?? '',
                    'vehicle_description' => $vehicle->vehicle_description ?? '',
                    'last_location_latitude' => $user->last_location_latitude ?? '',
                    'last_location_longitude' => $user->last_location_longitude ?? '',
                ]);
            }

            return $mapping;
        });
    }


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
}
