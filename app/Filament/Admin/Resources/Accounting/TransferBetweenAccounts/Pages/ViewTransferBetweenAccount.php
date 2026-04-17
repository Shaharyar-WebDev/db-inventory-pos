<?php

namespace App\Filament\Admin\Resources\Accounting\TransferBetweenAccounts\Pages;

use App\Filament\Admin\Resources\Accounting\TransferBetweenAccounts\TransferBetweenAccountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransferBetweenAccount extends ViewRecord
{
    protected static string $resource = TransferBetweenAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
