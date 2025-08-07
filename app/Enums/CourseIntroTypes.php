<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum CourseIntroTypes: string
{
    use EnumUtils;

    case Banner = 'banner';
    case IntroVideo = 'introVideo';
}
