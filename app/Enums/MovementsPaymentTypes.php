<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TLira()
 * @method static static Dollar()
 */
final class MovementsPaymentTypes extends Enum
{
    const TLira = 0;
    const Dollar = 1;

    public static function getPaymentTypes(): array
    {
        $allKeys = MovementsPaymentTypes::asArray();
        return $allKeys;
    }
}
