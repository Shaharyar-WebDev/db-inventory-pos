<?php

namespace App\Filament\Outlet\Resources\Accounting\Payments\Pages;

use App\Exports\PaymentExport;
use App\Filament\Outlet\Resources\Accounting\Payments\PaymentResource;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LedgerExportAction::configure(PaymentExport::class)
                ->fileName(function (?Model $record, ?Outlet $outlet) {
                    return "payment_export";
                })
                ->isOutletRequired(false)
                ->hasOutletSelectionSchema(false)
                ->make(),
        ];
    }
}
