<?php

namespace App\Models\Traits;

use App\Enums\TransactionType;

trait HasTransactionType
{
    public function initializeHasTransactionType(): void
    {
        $this->casts['transaction_type'] = TransactionType::class;
    }
}
