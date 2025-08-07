<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum CourseStatuses: string
{
    use EnumUtils;

    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Upcoming = 'upcoming';
}
