<?php

namespace App\Filament\Outlet\Resources\Accounting\Receipts\Pages;

use App\Filament\Outlet\Resources\Accounting\Receipts\ReceiptResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReceipt extends ViewRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
