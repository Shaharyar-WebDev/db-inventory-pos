<?php

namespace App\Filament\Outlet\Resources\Master\Products\Schemas;

use App\Models\Master\Product;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('code')
                    ->placeholder('-'),
                TextEntry::make('thumbnail')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('additional_images')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('attachments')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('unit_id')
                    ->numeric(),
                TextEntry::make('category_id')
                    ->numeric(),
                TextEntry::make('brand_id')
                    ->numeric(),
                TextEntry::make('cost_price')
                    ->money(),
                TextEntry::make('selling_price')
                    ->money(),
                TextEntry::make('tags')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Product $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
