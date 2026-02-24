<?php

namespace App\Enums;

enum ReceiptStatus: string
{
    case PENDING = 'pending';

    case APPROVED = 'approved';
}
