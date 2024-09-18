<?php declare(strict_types=1);

namespace App\Enums\UserEnums;

use BenSampo\Enum\Enum;

/**
 * @method static static MALE()
 * @method static static FEMALE()
 */
final class UserGender extends Enum
{
    const male = 0;
    const female = 1;
}
