<?php

namespace App\Enums;

enum ReceiptStatus: string
{
    case PENDING = 'pending';

    case APPROVED = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
        };
    }
}
