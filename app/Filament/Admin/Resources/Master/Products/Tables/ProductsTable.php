<?php

namespace App\Filament\Admin\Resources\Master\Products\Tables;

use App\Exports\InventoryLedgerExport;
use App\Filament\Admin\Resources\Master\Products\Actions\ViewProductStockByOutletAction;
use App\Filament\Admin\Resources\Master\Products\Actions\ViewProductValueByOutletAction;
use App\Models\Outlet\Outlet;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->circular()
                    ->imageSize(80)
                    ->placeholder('---')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')
                    ->copyable(),
                // TextColumn::make('code')
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->copyable(),
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
                    ->quantity()
                    ->searchable(false)
                    ->suffix(fn($record) => ' ' . ($record->unit?->symbol ?? ''))
                    ->action(
                        ViewProductStockByOutletAction::make()
                    )
                    ->sortable(),
                TextColumn::make('current_value')
                    ->currency()
                    ->sumCurrency()
                    ->searchable(false)
                    ->action(
                        ViewProductValueByOutletAction::make()
                    )
                    ->sortable(),
                TextColumn::make('current_avg_rate')
                    ->currency()
                    ->searchable(false)
                    ->summarize([
                        Summarizer::make()
                            ->label('Avg Rate')
                            ->using(function ($query) {
                                $totalValue = $query->sum('current_value');
                                $totalQty   = $query->sum('current_stock');

                                if ($totalQty == 0) {
                                    return 0;
                                }

                                return currency_format($totalValue / $totalQty);
                            }),
                    ])
                    ->sortable(),
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
            ->moreFilters([], [
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
                SelectFilter::make('unit')
                    ->relationship('unit', 'name'),
            ])
            ->groupedRecordActions([
                EditAction::make(),
                ViewProductStockByOutletAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('warning'),
                ViewProductValueByOutletAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('success'),
                DeleteAction::make(),
                // RestoreAction::make(),
                // ForceDeleteAction::make(),
                LedgerExportAction::configure(InventoryLedgerExport::class)
                    ->fileName(function (Model $record, ?Outlet $outlet) {
                        $suffix = $outlet ? "-{$outlet->name}" : '';
                        return "inventory_ledger_{$record->name}{$suffix}";
                    })
                    ->make(),
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
