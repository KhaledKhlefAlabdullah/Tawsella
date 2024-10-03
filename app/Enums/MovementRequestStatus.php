<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MovementRequestStatus extends Enum
{

    const Rejected = 0;
    const Accepted = 1;
    const Pending = 2;

    public static function getMovementStatuses(): array
    {
        $allKeys = MovementRequestStatus::asArray();

        // Define the key to exclude
        $valuesToExclude = [self::Pending];

        // Filter out the key
        $filteredKeys = array_filter($allKeys, function ($value) use ($valuesToExclude) {
            return !in_array($value, $valuesToExclude, true);
        }, ARRAY_FILTER_USE_BOTH);

        return $filteredKeys;
    }

}
