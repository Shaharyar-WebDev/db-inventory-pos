<?php

namespace App\Enums;

enum TransactionType: string
{
    case INVENTORY_ADJUSTMENT = 'inventory_adjustment';

    case ADJUSTMENT_REVERSAL = 'adjustment_reversal';

    case OPENING_BALANCE = 'opening_balance';

    case PURCHASE = 'purchase';

    case SALE = 'sale';
}
