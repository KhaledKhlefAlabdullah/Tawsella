<?php

namespace App\Enums;

enum UserGender: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    public static function values(): array
    {
        return array_filter(
            array_map(fn($role) => $role->value, self::cases()),
            fn($value) => $value !== 'admin'
        );
    }
}
