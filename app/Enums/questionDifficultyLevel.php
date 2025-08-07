<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum questionDifficultyLevel: string
{
    use EnumUtils;

    case Easy = 'easy';
    case Medium = 'medium';
    case Hard = 'hard';
}
