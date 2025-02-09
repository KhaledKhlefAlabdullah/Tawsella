<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TL()
 * @method static static USD()
 * @method static static SYP()
 */
final class PaymentTypesEnum extends Enum
{
    const TL = 0;
    const USD = 1;
    const SYP = 2;
}
