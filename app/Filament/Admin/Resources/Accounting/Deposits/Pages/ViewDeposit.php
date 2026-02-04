<?php

namespace App\Filament\Admin\Resources\Accounting\Deposits\Pages;

use App\Filament\Admin\Resources\Accounting\Deposits\DepositResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeposit extends ViewRecord
{
    protected static string $resource = DepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
