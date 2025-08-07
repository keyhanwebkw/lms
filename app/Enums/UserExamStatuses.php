<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum UserExamStatuses: string
{
    use EnumUtils;

    case NotStarted = 'notStarted';
    case InProgress = 'inProgress';
    case Failed = 'failed';
    case Passed = 'passed';
}
