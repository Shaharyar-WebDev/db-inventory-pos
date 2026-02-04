<?php

namespace App\Filament\Admin\Resources\Accounting\Deposits\Schemas;

use App\Models\Accounting\Deposit;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepositInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('deposit_number'),
                TextEntry::make('account_id')
                    ->numeric(),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('remarks')
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
                    ->visible(fn (Deposit $record): bool => $record->trashed()),
            ]);
    }
}
