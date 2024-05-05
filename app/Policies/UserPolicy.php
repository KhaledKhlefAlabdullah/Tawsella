<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Constants defining user types
    const ADMIN = 'admin';
    const CUSTOMER = 'customer';
    const TAXI_DRIVER = 'taxi driver';
    const TRANSPORT_CAR_DRIVER = 'transport car driver';
    const MOTORCYCLIST = 'motorcyclist';

    public function __construct()
    {
        // Constructor, currently empty
    }

    /**
     * Check if the user is an admin.
     *
     * @param User $user The user to check.
     * @return bool Returns true if the user is an admin, false otherwise.
     */
    public function is_admin(User $user): bool
    {
        return $user->user_type == self::ADMIN;
    }

    /**
     * Check if the user is a customer.
     *
     * @param User $user The user to check.
     * @return bool Returns true if the user is a customer, false otherwise.
     */
    public function is_customer(User $user): bool
    {
        return $user->user_type == self::CUSTOMER;
    }

    /**
     * Check if the user is a driver (taxi driver, transport car driver, or motorcyclist).
     *
     * @param User $user The user to check.
     * @return bool Returns true if the user is a driver, false otherwise.
     */
    public function is_driver(User $user): bool
    {
        return $user->user_type == self::TAXI_DRIVER || $user->user_type == self::TRANSPORT_CAR_DRIVER || $user->user_type == self::MOTORCYCLIST;
    }
}
