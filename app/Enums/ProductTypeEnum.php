<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProductTypeEnum extends Enum
{
    const Items =   0;
    const LKCCourses =   1;
    const LMACourses =   2;
    const LCCourses =   3;
    const Services = 4;
}
