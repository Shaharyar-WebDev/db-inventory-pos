<?php

namespace App\Filament\Admin\Resources\Accounting\Accounts\Tables;

use Filament\Tables\Table;
use App\Models\Outlet\Outlet;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use App\Exports\AccountLedgerExport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use App\Support\Actions\LedgerExportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class AccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->copyable(),
                TextColumn::make('opening_balance')
                    ->currency(),
                TextColumn::make('current_balance')
                    ->currency()
                    ->sumCurrency()
                    ->searchable(false)
                    ->copyable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->moreFilters([
                // TrashedFilter::make(),
            ])
            ->groupedRecordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                // ForceDeleteAction::make(),
                // RestoreAction::make(),
                LedgerExportAction::configure(AccountLedgerExport::class)
                    ->fileName(function (Model $record, ?Outlet $outlet) {
                        $suffix = $outlet ? "-{$outlet->name}" : '';
                        return "account_ledger_{$record->name}{$suffix}";
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
