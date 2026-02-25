<?php

use App\Models\Purchase\Purchase;

return [

    'prevent_record_deletion_if_ledger_exists' => [
        Purchase::class => true,
    ],

    'marketing_footer_enabled' => true,
    'marketing_headline' => 'Custom Business Software by',
    'developer_name' => 'Shaharyar Ahmed',
    'developer_email' => 'shery.codes@gmail.com',
    'developer_contact' => '0315-4573767',
    'developer_portofolio' => '',

];
