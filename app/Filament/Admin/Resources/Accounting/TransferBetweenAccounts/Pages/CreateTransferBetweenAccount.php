<?php

namespace App\Filament\Admin\Resources\Accounting\TransferBetweenAccounts\Pages;

use App\Filament\Admin\Resources\Accounting\TransferBetweenAccounts\TransferBetweenAccountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransferBetweenAccount extends CreateRecord
{
    protected static string $resource = TransferBetweenAccountResource::class;
}
