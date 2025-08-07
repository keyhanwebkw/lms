<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum CourseCategoryStatuses: string
{
    use EnumUtils;

    case Active = 'active';
    case Inactive = 'inactive';
}
