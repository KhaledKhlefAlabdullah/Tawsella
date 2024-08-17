<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\User;

class UserPolicy
{
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
        return $user->hasRole(UserType::ADMIN()->key);
    }

    /**
     * Check if the user is a customer.
     *
     * @param User $user The user to check.
     * @return bool Returns true if the user is a customer, false otherwise.
     */
    public function is_customer(User $user): bool
    {
        return $user->hasRole(UserType::CUSTOMER()->key);
    }

    /**
     * Check if the user is a driver (taxi driver, transport car driver, or motorcyclist).
     *
     * @param User $user The user to check.
     * @return bool Returns true if the user is a driver, false otherwise.
     */
    public function is_driver(User $user): bool
    {
        return $user->hasAnyRole([
            UserType::TAXIDRIVER()->key,
            UserType::TRANSPORTCARDRIVER()->key,
            UserType::MOTOROCYCLIST()->key
        ]);
    }

    /**
     * Check if the user is not a driver (taxi driver, transport car driver, or motorcyclist).
     *
     * @param User $user The user to check.
     * @return bool Returns true if the user is not a driver, false otherwise.
     */
    public function not_driver(User $user): bool
    {
        return !$user->hasAnyRole([
            UserType::TAXIDRIVER()->key,
            UserType::TRANSPORTCARDRIVER()->key,
            UserType::MOTOROCYCLIST()->key
        ]);
    }

    /**
     * Check if the user is admin or driver (taxi driver, transport car driver, or motorcyclist).
     *
     * @param User $user The user to check.
     * @return bool Returns true if the user is an admin or driver, false otherwise.
     */
    public function admin_and_driver(User $user): bool
    {
        return !$user->hasRole(UserType::CUSTOMER()->key);
    }
}
