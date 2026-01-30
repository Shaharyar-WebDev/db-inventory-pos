<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Tables;

use Filament\Tables\Table;
use App\Enums\DiscountType;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale_number')
                    ->copyable(),
                TextColumn::make('customer.name')
                    ->copyable(),
                TextColumn::make('total')
                    ->currency(),
                TextColumn::make('discount_type')
                    ->copyable()
                    ->badge(),
                TextColumn::make('discount_value')
                    ->numeric()
                    ->prefix(fn($record) => $record->discount_type === DiscountType::FIXED->value ? app_currency_symbol() : '')
                    ->suffix(fn($record) => $record->discount_type === DiscountType::PERCENT->value ? ' %' : '')
                    ->copyable(),
                TextColumn::make('grand_total')
                    ->currency(),
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
            ->filters([
                TrashedFilter::make(),
            ])
            ->groupedRecordActions([
                EditAction::make(),
                DeleteAction::make(),
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
