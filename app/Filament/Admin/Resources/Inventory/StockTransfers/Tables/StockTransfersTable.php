<?php

namespace App\Filament\Admin\Resources\Inventory\StockTransfers\Tables;

use App\Support\Actions\PdfDownloadAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StockTransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transfer_number')
                    ->searchable(),
                TextColumn::make('fromOutlet.name')
                    ->copyable(),
                TextColumn::make('toOutlet.name')
                    ->copyable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->moreFilters([
                SelectFilter::make('from_outlet_id')
                    ->label('From Outlet')
                    ->relationship('fromOutlet', 'name'),
                SelectFilter::make('to_outlet_id')
                    ->label('To Outlet')
                    ->relationship('toOutlet', 'name'),
            ])
            ->groupedRecordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                PdfDownloadAction::make('partials.pdf.stock-transfer', fn(Model $record) => $record->transfer_number)
                    ->download(),
                PdfDownloadAction::make('partials.pdf.stock-transfer', fn(Model $record) => $record->transfer_number)
                    ->print()
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
