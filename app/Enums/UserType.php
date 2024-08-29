<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ADMIN()
 * @method static static CUSTOMER()
 * @method static static TAXIDRIVER()
 * @method static static TRANSPORTCARDRIVER()
 * @method static static MOTOROCYCLIST()
 */
final class UserType extends Enum
{
    const ADMIN = 0;
    const CUSTOMER = 1;
    const TAXIDRIVER = 2;
    const TRANSPORTCARDRIVER = 3;
    const MOTOROCYCLIST = 4;

    public static function getUsersTypes()
    {
        $allKeys = UserType::getKeys();

        // Define the key to exclude
        $keyToExclude = 'Administrator';

        // Filter out the key
        $filteredKeys = array_filter($allKeys, function ($key) use ($keyToExclude) {
            return $key !== $keyToExclude;
        });

        // Re-index array if needed
        $filteredKeys = array_values($filteredKeys);
        return $filteredKeys;
    }

    /**
     * Get all service types except ADMIN and CUSTOMER.
     *
     * @return array
     */
    public static function getServicesTypes()
    {
        // Get all keys and values from the UserType enum
        $allTypes = UserType::asArray();

        // Define the keys to exclude
        $keysToExclude = [self::ADMIN, self::CUSTOMER];

        // Filter out the excluded keys
        $filteredTypes = array_filter($allTypes, function ($value, $key) use ($keysToExclude) {
            return !in_array($value, $keysToExclude, true);
        }, ARRAY_FILTER_USE_BOTH);

        // Re-index array if needed
        $array = [
            'Taxi' => $filteredTypes['TAXIDRIVER'],
            'TransportCars' => $filteredTypes['TRANSPORTCARDRIVER'],
            'Motorcycle' => $filteredTypes['MOTOROCYCLIST'],
        ];

        return $array;
    }
}
