<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Pages;

use App\Exports\SalesExport;
use App\Filament\Outlet\Resources\Sale\Sales\SaleResource;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LedgerExportAction::configure(SalesExport::class)
                ->fileName(function (?Model $record, ?Outlet $outlet) {
                    $outlet = Filament::getTenant();
                    return "sales_export_{$outlet->name}";
                })
                ->isOutletRequired(false)
                ->hasOutletSelectionSchema(false)
                ->make(),
        ];
    }
}
