<?php

namespace App\Support\Actions;

use Filament\Actions\Action;

class RefreshAction
{
    public static function make()
    {
        return Action::make('refresh')
            ->color('info')
            ->action(function ($livewire) {
                $livewire->dispatch('refresh');
            })
            ->icon('heroicon-o-arrow-path');
    }
}
