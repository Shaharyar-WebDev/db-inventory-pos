<?php
namespace App\Filament\Outlet\Resources\Inventory\InventoryAdjustments\Tables;

use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('adjustment_number')
                    ->copyable(),
            ])
            ->moreFilters([
                // TrashedFilter::make(),
            ])
            ->groupedRecordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                PdfDownloadAction::make('partials.pdf.inventory-adjustment', fn(Model $record) => $record->adjustment_number)
                    ->download(),
                PdfDownloadAction::make('partials.pdf.inventory-adjustment', fn(Model $record) => $record->adjustment_number)
                    ->print(),
                // ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ]);
    }
}
