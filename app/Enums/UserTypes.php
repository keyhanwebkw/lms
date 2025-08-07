<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

/**
 * Gender
 */
enum UserTypes: string
{
    use EnumUtils;

    case Parent = 'parent';
    case Child = 'child';
    case Consultant = 'consultant';
}
