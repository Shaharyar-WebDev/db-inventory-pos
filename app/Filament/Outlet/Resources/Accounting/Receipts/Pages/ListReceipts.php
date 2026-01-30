<?php

namespace App\Filament\Outlet\Resources\Accounting\Receipts\Pages;

use App\Filament\Outlet\Resources\Accounting\Receipts\ReceiptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReceipts extends ListRecords
{
    protected static string $resource = ReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
