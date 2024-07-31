<?php

namespace App\Enums;

enum UserType: string
{
    case CUSTOMER = 'customer';
    case TAXIDRIVER = 'taxi driver';
    case TRANSPORTCARDRIVER = 'transport car driver';
    case MOTOROCYCLIST = 'motorcyclist';
    case ADMIN = 'admin';

    public static function values(): array
    {
        return array_filter(
            array_map(fn($role) => $role->value, self::cases()),
            fn($value) => $value !== 'admin'
        );
    }
}
