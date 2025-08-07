<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum OrderProductTypes: string
{
    use EnumUtils;

    case physical = 'physical';
    case virtual = 'virtual';
    case both = 'both';
}
