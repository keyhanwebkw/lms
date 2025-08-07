<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum CourseLevels: string
{
    use EnumUtils;

    case Basic = 'basic';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';

}
