<?php

namespace App\Enums;

enum Status: string
{
    case ACTIVE = 'active';

    case IN_ACTIVE = 'in_active';

    case PENDING = 'pending';

    case APPROVED = 'approved';

    public function label(): string
    {
        return str($this->value)->replace('_', ' ')->title()->toString();
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::IN_ACTIVE => 'danger',
        };
    }
}
