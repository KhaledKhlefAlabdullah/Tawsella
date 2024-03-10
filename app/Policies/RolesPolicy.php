<?php

namespace App\Policies;

use App\Models\User;

class RolesPolicy
{
    const ADMIN = 'admin';

    const DRIVER = 'driver';

    const CUSTOMER = 'customer';
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // For admin role
    public function admin(User $user){
        return $user->user_type == 'admin';
    }

     // For driver role
     public function driver(User $user){
        return $user->user_type == 'driver';
    }

     // For admin customer
     public function customer(User $user){
        return $user->user_type == 'customer';
    }
}
