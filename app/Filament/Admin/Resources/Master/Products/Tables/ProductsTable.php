<?php

namespace App\Filament\Admin\Resources\Master\Products\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions\BulkActionGroup;
use App\Exports\InventoryLedgerExport;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\Admin\Resources\Master\Products\Actions\ViewProductStockByOutletAction;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->circular()
                    ->imageSize(50)
                    ->placeholder('---')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')
                    ->copyable(),
                TextColumn::make('code')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable(),
                TextColumn::make('category.name')
                    ->copyable(),
                TextColumn::make('brand.name')
                    ->copyable(),
                TextColumn::make('unit.name')
                    ->copyable(),
                TextColumn::make('cost_price')
                    ->currency()
                    ->copyable(),
                TextColumn::make('selling_price')
                    ->currency()
                    ->copyable(),
                TextColumn::make('current_stock')
                    ->default(0)
                    ->searchable(false)
                    ->suffix(fn($record) => ' ' . ($record->unit?->symbol ?? ''))
                    ->action(
                        ViewProductStockByOutletAction::make()
                    )
                    ->sortable(false),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->groupedRecordActions([
                EditAction::make(),
                ViewProductStockByOutletAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('warning'),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
