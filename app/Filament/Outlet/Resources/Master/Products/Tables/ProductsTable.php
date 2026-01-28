<?php

namespace App\Filament\Outlet\Resources\Master\Products\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

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
                TextColumn::make('unit.name')
                    ->copyable(),
                // TextColumn::make('cost_price')
                //     ->prefix(app_currency_symbol())
                //     ->copyable(),
                TextColumn::make('selling_price')
                    ->prefix(app_currency_symbol())
                    ->copyable(),
                 TextColumn::make('current_outlet_stock')
                    ->searchable(false)
                    ->default(0)
                    ->suffix(fn ($record) => ' '.($record->unit?->symbol ?? ''))
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
                // TrashedFilter::make(),
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ]);
    }
}
