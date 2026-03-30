<?php

namespace App\Filament\Outlet\Resources\Accounting\Receipts\Pages;

use App\Exports\ReceiptExport;
use App\Filament\Outlet\Resources\Accounting\Receipts\ReceiptResource;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListReceipts extends ListRecords
{
    protected static string $resource = ReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LedgerExportAction::configure(ReceiptExport::class)
                ->fileName(function (?Model $record, ?Outlet $outlet) {
                    return "receipt_export";
                })
                ->isOutletRequired(false)
                ->hasOutletSelectionSchema(false)
                ->make(),
        ];
    }
}
