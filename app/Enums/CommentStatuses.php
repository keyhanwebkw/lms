<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum CommentStatuses: string
{
    use EnumUtils;
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
