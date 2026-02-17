<?php

namespace App\Filament\Outlet\Resources\Sale\SaleReturns\Schemas;

use App\Models\Sale\SaleReturn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SaleReturnInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('return_number'),
                // TextEntry::make('sale.id')
                //     ->label('Sale'),
                // TextEntry::make('description')
                //     ->placeholder('-')
                //     ->columnSpanFull(),
                // TextEntry::make('total')
                //     ->numeric(),
                // TextEntry::make('discount_type')
                //     ->badge(),
                // TextEntry::make('discount_value')
                //     ->numeric(),
                // TextEntry::make('grand_total')
                //     ->numeric(),
                // TextEntry::make('outlet.name')
                //     ->label('Outlet')
                //     ->placeholder('-'),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('deleted_at')
                //     ->dateTime()
                //     ->visible(fn (SaleReturn $record): bool => $record->trashed()),
            ]);
    }
}
