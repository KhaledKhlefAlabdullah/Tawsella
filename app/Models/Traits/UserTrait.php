<?php

namespace App\Models\Traits;

use App\Enums\UserType;
use App\Models\Movement;
use App\Models\Rating;

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
}
