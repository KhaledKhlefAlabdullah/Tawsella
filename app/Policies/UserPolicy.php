<?php

namespace App\Policies;

use App\Models\User;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class UserPolicy
{

    const ADMIN = 'admin';
    const CUSTOMER = 'customer';
    const TAXI_DRIVER = 'taxi driver';
    const TRANSPORT_CAR_DRIVER = 'transport car driver';
    const MOTORCYCLIST = 'motorcyclist';
    public function __construct()
    {
        //
    }

    public function is_admin(User $user):bool
    {
        return $user->user_type == $this::ADMIN;
    }

    public function is_customer(User $user):bool
    {
        return $user->user_type == $this::CUSTOMER;
    }

    public function is_driver(USer $user):bool
    {
        return $user->user_type == $this::TAXI_DRIVER || $user->user_type == $this::TRANSPORT_CAR_DRIVER || $user->user_type == $this::MOTORCYCLIST;
    }
}
