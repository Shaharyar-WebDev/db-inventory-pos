<?php

namespace App\Filament\Outlet\Resources\Master\Customers\Schemas;

use App\Models\Master\Customer;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('name'),
                // TextEntry::make('city.name')
                //     ->label('City')
                //     ->placeholder('-'),
                // TextEntry::make('area.name')
                //     ->label('Area')
                //     ->placeholder('-'),
                // TextEntry::make('address')
                //     ->placeholder('-')
                //     ->columnSpanFull(),
                // TextEntry::make('contact')
                //     ->placeholder('-'),
                // TextEntry::make('opening_balance')
                //     ->numeric(),
                // TextEntry::make('photo')
                //     ->placeholder('-'),
                // TextEntry::make('attachments')
                //     ->placeholder('-')
                //     ->columnSpanFull(),
                // TextEntry::make('deleted_at')
                //     ->dateTime()
                //     ->visible(fn (Customer $record): bool => $record->trashed()),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
            ]);
    }
}
