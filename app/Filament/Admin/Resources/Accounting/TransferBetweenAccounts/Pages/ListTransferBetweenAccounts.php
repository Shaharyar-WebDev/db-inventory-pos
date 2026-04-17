<?php

namespace App\Filament\Admin\Resources\Accounting\TransferBetweenAccounts\Pages;

use App\Filament\Admin\Resources\Accounting\TransferBetweenAccounts\TransferBetweenAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransferBetweenAccounts extends ListRecords
{
    protected static string $resource = TransferBetweenAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
