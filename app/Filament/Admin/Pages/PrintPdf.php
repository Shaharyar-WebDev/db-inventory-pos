<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Resources\Inventory\StockTransfers\StockTransferResource;
use Filament\Resources\Pages\EditRecord;

class PrintPdf extends EditRecord
{
    // protected string $view = 'filament.admin.pages.print-pdf';

    protected static string $resource = StockTransferResource::class;

    public function mount(int|string $record): void
    {
        dd();
    }
}
