<?php

namespace App\Filament\Admin\Resources\Master\Customers\Actions;

use App\Exports\CustomerLedgerExport as CLExport;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use Illuminate\Database\Eloquent\Model;

class CustomerLedgerExportAction
{

    public static function make()
    {
        return LedgerExportAction::configure(CLExport::class)
            ->fileName(function (Model $record, ?Outlet $outlet) {
                $suffix = $outlet ? "-{$outlet->name}" : '';
                return "customer_ledger_{$record->name}{$suffix}";
            })
            ->isOutletRequired(false)
            ->hasOutletSelectionSchema(false)
            ->make();
    }
}
