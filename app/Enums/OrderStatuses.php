<?php

namespace App\Enums;

use Keyhanweb\Subsystem\Enums\Traits\EnumUtils;

enum OrderStatuses: string
{
    use EnumUtils;

    case pending = 'pending';
    case waitingForPayment = 'waitingForPayment';
    case confirm = 'confirm';
    case cancel = 'cancel';
    case refund = 'refund';
    case deliver = 'deliver';
    case complete = 'complete';

    public static function availableForUser(): array
    {
        return [
            self::pending->value,
            self::waitingForPayment->value,
        ];
    }

    public static function unavailableForUser(): array
    {
        return array_diff(self::cases(), self::availableForUser());
    }
}
