<?php

namespace App\Filament\Admin\Resources\Master\Suppliers\Tables;

use Filament\Tables\Table;
use App\Models\Outlet\Outlet;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\Exports\SupplierLedgerExport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\TrashedFilter;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\ForceDeleteBulkAction;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->copyable(),
                TextColumn::make('contact')
                    ->disableNumericFormatting()
                    ->copyable(),
                TextColumn::make('opening_balance')
                    ->currency()
                    ->copyable(),
                TextColumn::make('current_balance')
                    ->currency()
                    ->searchable(false)
                    ->sumCurrency()
                    ->tooltip(function ($state) {
                        if ($state < 0) {
                            return " Debit";
                        }

                        if ($state > 0) {
                            return " Credit";
                        }

                        return null;
                    })
                    ->copyable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // TrashedFilter::make(),
            ])
            ->groupedRecordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                LedgerExportAction::configure(SupplierLedgerExport::class)
                    ->fileName(function (Model $record, ?Outlet $outlet) {
                        $suffix = $outlet ? "-{$outlet->name}" : '';
                        return "supplier_ledger_{$record->name}{$suffix}";
                    })
                    ->isOutletRequired(false)
                    ->hasOutletSelectionSchema(false)
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
