<?php declare(strict_types=1);

namespace App\Enums\UserEnums;

use BenSampo\Enum\Enum;

/**
 * @method static static Admin()
 * @method static static Customer()
 * @method static static TaxiDriver()
 * @method static static TransportCarDriver()
 * @method static static Motorocyclist()
 */
final class UserType extends Enum
{
    const Admin = 0;
    const Customer = 1;
    const TaxiDriver = 2;

    public static function getUsersTypes()
    {
        $allKeys = UserType::getKeys();

        // Define the key to exclude
        $keyToExclude = UserType::Admin()->key;

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
        $keysToExclude = [self::Admin, self::Customer];

        // Filter out the excluded keys
        $filteredTypes = array_filter($allTypes, function ($value, $key) use ($keysToExclude) {
            return !in_array($value, $keysToExclude, true);
        }, ARRAY_FILTER_USE_BOTH);

        return $filteredTypes;
    }
}
