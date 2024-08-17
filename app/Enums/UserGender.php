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
final class UserGender extends Enum
{
    const MALE = 'male';
    const FEMALE = 'female';
}
