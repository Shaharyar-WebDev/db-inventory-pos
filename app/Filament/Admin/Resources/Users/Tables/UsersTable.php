<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->toggleable()
                    ->searchable(),
                // TextColumn::make('email_verified_at')
                // ->dateTime()
                // ->sortable(),
                TextColumn::make('roles.name')
                    ->placeholder('---')
                    ->toggleable()
                    ->badge(),
                TextColumn::make('outlets.name')
                    ->placeholder('---')
                    ->toggleable()
                    ->color('info')
                    ->badge(),
                TextColumn::make('created_at')
                    ->toggleable()
                    ->date(app_date_time_format())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->date(app_date_time_format())
                    ->sortable()
                    ->toggleable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
