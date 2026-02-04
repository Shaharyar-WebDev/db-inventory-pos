<?php

namespace App\Filament\Admin\Resources\Accounting\Deposits\Pages;

use App\Filament\Admin\Resources\Accounting\Deposits\DepositResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeposit extends CreateRecord
{
    protected static string $resource = DepositResource::class;
}
