<?php

namespace App\Filament\Admin\Resources\Master\Customers\Pages;

use App\Exports\CustomerBalancesExport;
use App\Filament\Admin\Resources\Master\Customers\CustomerResource;
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
            // LedgerExportAction::configure(CustomerBalancesExport::class)
            //     ->fileName(function (?Model $record, ?Outlet $outlet) {
            //         return "customer_balances_export";
            //     })
            //     ->isOutletRequired(false)
            //     ->hasOutletSelectionSchema(false)
            //     ->make(),
        ];
    }
}
