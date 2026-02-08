<?php

namespace App\Enums;

enum TransactionType: string
{
    case INVENTORY_ADJUSTMENT = 'inventory_adjustment';

    case ADJUSTMENT_REVERSAL = 'adjustment_reversal';

    case OPENING_BALANCE = 'opening_balance';

    case PURCHASE = 'purchase';

    case SALE = 'sale';

    case STOCK_TRANSFER_IN = 'stock_transfer_in';

    case STOCK_TRANSFER_OUT = 'stock_transfer_out';

    case REFUND_OR_ADJUSTMENT = 'refund_or_adjustment';

    case PAYMENT = 'payment';
}
