<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TL()
 * @method static static USD()
 */
final class PaymentTypesEnum extends Enum
{
    const TL = 0;
    const USD = 1;
}
