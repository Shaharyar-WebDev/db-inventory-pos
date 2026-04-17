<?php

namespace App\Filament\Admin\Resources\Accounting\TransferBetweenAccounts\Pages;

use App\Filament\Admin\Resources\Accounting\TransferBetweenAccounts\TransferBetweenAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransferBetweenAccount extends EditRecord
{
    protected static string $resource = TransferBetweenAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
