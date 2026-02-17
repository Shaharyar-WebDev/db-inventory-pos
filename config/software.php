<?php

use App\Models\Purchase\Purchase;

return [

    'prevent_record_deletion_if_ledger_exists' => [
        Purchase::class => true,
    ]

];
