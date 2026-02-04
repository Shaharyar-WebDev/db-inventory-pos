<?php

namespace App\Filament\Admin\Resources\Accounting\Deposits\Pages;

use App\Filament\Admin\Resources\Accounting\Deposits\DepositResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeposits extends ListRecords
{
    protected static string $resource = DepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
