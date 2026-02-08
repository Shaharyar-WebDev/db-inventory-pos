<?php

namespace App\Support\Actions;

use Filament\Actions\Action;

class RefreshAction
{
    public static function make()
    {
        return Action::make('refresh')
            ->color('info')
            ->action(fn($livewire) =>  $livewire->refresh())
            ->icon('heroicon-o-arrow-path');
    }
}
