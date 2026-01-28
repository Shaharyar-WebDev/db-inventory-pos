<?php

namespace App\Filament\Outlet\Resources\Purchase\Purchases\Schemas;

use App\Models\Purchase\Purchase;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PurchaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('purchase_number'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('outlet.name')
                    ->label('Outlet'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Purchase $record): bool => $record->trashed()),
            ]);
    }
}
