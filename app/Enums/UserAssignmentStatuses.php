<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum UserAssignmentStatuses: string
{
    use EnumUtils;

    case InProgress = 'inProgress';
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Resubmitted = 'resubmitted';
}
