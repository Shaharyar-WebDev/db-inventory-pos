<?php

namespace App\Filament\Outlet\Resources\Master\Customers\Pages;

use App\Exports\CustomerExport;
use App\Filament\Outlet\Resources\Master\Customers\CustomerResource;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LedgerExportAction::configure(CustomerExport::class)
                ->fileName(function (?Model $record, ?Outlet $outlet) {
                    return "customer_export";
                })
                ->isOutletRequired(false)
                ->hasOutletSelectionSchema(false)
                ->make(),
        ];
    }
}
