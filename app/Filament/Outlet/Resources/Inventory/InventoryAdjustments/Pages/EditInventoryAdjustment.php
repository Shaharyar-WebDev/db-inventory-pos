<?php

namespace App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Pages;

use App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\InventoryAdjustmentResource;
use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditInventoryAdjustment extends EditRecord
{
    protected static string $resource = InventoryAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            DeleteAction::make(),
            PdfDownloadAction::make('partials.pdf.inventory-adjustment', fn(Model $record) => $record->adjustment_number)
                ->download(),
            PdfDownloadAction::make('partials.pdf.inventory-adjustment', fn(Model $record) => $record->adjustment_number)
                ->print()
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
        ];
    }
}
