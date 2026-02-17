<?php

namespace App\Enums;

enum TransactionType: string
{
    case INVENTORY_ADJUSTMENT = 'inventory_adjustment';

    case ADJUSTMENT_REVERSAL = 'adjustment_reversal';

    case OPENING_BALANCE = 'opening_balance';

    case PURCHASE = 'purchase';

    case PURCHASE_RETURN = 'purchase_return';

    case SALE = 'sale';
    case SALE_RETURN = 'sale_return';

    case STOCK_TRANSFER_IN = 'stock_transfer_in';

    case STOCK_TRANSFER_OUT = 'stock_transfer_out';

    case PAYMENT_REFUND_OR_ADJUSTMENT = 'payment_refund_or_adjustment';

    case RECEIPT_REFUND_OR_ADJUSTMENT = 'receipt_refund_or_adjustment';

    case PAYMENT = 'payment';

    case RECEIPT = 'receipt';

    case DEPOSIT = 'deposit';

    case EXPENSE = 'expense';

    public function label(): string
    {
        return str($this->value)->replace('_', ' ')->title()->toString();
    }
}
