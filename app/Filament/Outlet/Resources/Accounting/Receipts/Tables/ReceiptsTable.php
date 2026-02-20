<?php

namespace App\Filament\Outlet\Resources\Accounting\Receipts\Tables;

use App\Enums\PanelId;
use App\Filament\Outlet\Resources\Master\Customers\CustomerResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ReceiptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('receipt_number')
                    ->copyable(),
                TextColumn::make('customer.name')
                    ->url(
                        filament()->auth()->user()->isSuperAdmin() ? fn($state) => CustomerResource::getUrl('index', [
                            'search' => $state
                        ], panel: PanelId::ADMIN->value) : '',
                        true
                    )
                    ->copyable(!filament()->auth()->user()->isSuperAdmin()),
                TextColumn::make('account.name')
                    ->copyable(),
                TextColumn::make('amount')
                    ->sumCurrency()
                    ->currency(),
                TextColumn::make('remarks')
                    ->desc(),
                // TextColumn::make('outlet.name')
                //     ->searchable(),
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
            ], [
                SelectFilter::make('account')
                    ->relationship('account', 'name'),
                SelectFilter::make('customer')
                    ->relationship('customer', 'name')
            ])
            ->groupedRecordActions([
                EditAction::make(),
                DeleteAction::make(),
                // ForceDeleteAction::make(),
                // RestoreAction::make()
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
