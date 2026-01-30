<?php

namespace App\Filament\Admin\Resources\Inventory\StockTransfers\Schemas;

use App\Models\Inventory\StockTransfer;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockTransferInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('transfer_number'),
                TextEntry::make('from_outlet_id')
                    ->numeric(),
                TextEntry::make('to_outlet_id')
                    ->numeric(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (StockTransfer $record): bool => $record->trashed()),
            ]);
    }
}
