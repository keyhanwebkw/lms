<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

/**
 * ActivationStatuses
 */
enum Language: string
{
    use EnumUtils;

    case FA = 'fa';
    case EN = 'en';
}
