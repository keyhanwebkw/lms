<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum OrderDetailTypes: string
{
    use EnumUtils;

    case physical = 'physical';
    case virtual = 'virtual';
}
