<?php

namespace App\Filament\Outlet\Resources\Master\Customers\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ForceDeleteBulkAction;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->circular()
                    ->imageSize(80)
                    ->placeholder('---')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')
                    ->copyable(),
                TextColumn::make('contact')
                    ->disableNumericFormatting()
                    ->copyable(),
                TextColumn::make('city.name')
                    ->copyable(),
                TextColumn::make('area.name')
                    ->copyable(),
                // TextColumn::make('opening_balance')
                //     ->currency()
                //     ->copyable(),
                TextColumn::make('current_balance')
                    ->currency()
                    ->sumCurrency()
                    ->tooltip(function ($state) {
                        if ($state < 0) {
                            return " Credit";
                        }

                        if ($state > 0) {
                            return " Debit";
                        }

                        return null;
                    })
                    ->searchable(false)
                    ->copyable(),
                TextColumn::make('address')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('city')
                    ->relationship('city', 'name')
                    ->searchable()
                    ->preload(10)
                    ->optionsLimit(10),
                SelectFilter::make('area')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->preload(10)
                    ->optionsLimit(10),
            ])
            ->groupedRecordActions([
                EditAction::make(),
                DeleteAction::make(),
                // RestoreAction::make(),
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
