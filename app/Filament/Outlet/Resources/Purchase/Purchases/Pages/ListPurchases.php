<?php

namespace App\Filament\Outlet\Resources\Purchase\Purchases\Pages;

use App\Exports\PurchaseExport;
use App\Filament\Outlet\Resources\Purchase\Purchases\PurchaseResource;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListPurchases extends ListRecords
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LedgerExportAction::configure(PurchaseExport::class)
                ->fileName(function (?Model $record, ?Outlet $outlet) {
                    return "purchase_export";
                })
                ->isOutletRequired(false)
                ->hasOutletSelectionSchema(false)
                ->make(),
        ];
    }
}
