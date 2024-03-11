<?php

namespace App\Policies;

use App\Models\User;

class RolesPolicy
{
    const ADMIN = 'admin';

    const DRIVER = 'driver';

    const CUSTOMER = 'customer';


    // For admin role
    public function admin(User $user){
        return $user->user_type == $this::ADMIN;
    }

     // For driver role
     public function driver(User $user){
        return $user->user_type == $this::DRIVER;
    }

     // For admin customer
     public function customer(User $user){
        return $user->user_type == $this::CUSTOMER;
    }
}
