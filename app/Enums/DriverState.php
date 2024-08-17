<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;


/**
 * @method static static Ready()
 * @method static static InBreak()
 * @method static static Reserved()
 */
final class DriverState extends Enum
{
    const Ready = 0;
    const InBreak = 1;
    const Reserved = 2;
}
