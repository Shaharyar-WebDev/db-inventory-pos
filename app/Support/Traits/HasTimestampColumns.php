<?php

declare(strict_types=1);

namespace App\Support\Traits;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

trait HasTimestampColumns
{
    public static function configureTable(Table $table): void
    {
        parent::configureTable($table);

        $table->pushColumns([
            TextColumn::make('creator.name')
                ->searchable(),

            TextColumn::make('editor.name')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
        ]);
    }
}
