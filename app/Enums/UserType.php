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
}
