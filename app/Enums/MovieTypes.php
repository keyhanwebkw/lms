<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum MovieTypes: string
{
    use EnumUtils;

    case Series = 'series'; // سریال
    case Film = 'film'; // سینمایی
}
