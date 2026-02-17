<?php

namespace App\Filament\Admin\Resources\Master\Products\Pages;

use App\Exports\InventoryLedgerExport;
use App\Filament\Admin\Resources\Master\Products\ProductResource;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            LedgerExportAction::configure(InventoryLedgerExport::class)
                ->fileName(function (Model $record, ?Outlet $outlet) {
                    $suffix = $outlet ? "-{$outlet->name}" : '';
                    return "inventory_ledger_{$record->name}{$suffix}";
                })
                ->make(),
        ];
    }
}
