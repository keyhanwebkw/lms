<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum EpisodeStatuses: string
{
    use EnumUtils;

    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
