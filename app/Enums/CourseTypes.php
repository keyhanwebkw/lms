<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum CourseTypes: string
{
    use EnumUtils;

    case Audio = 'audio';
    case Video = 'video';
}
