<?php

namespace App\Support\Components;

use Filament\Forms\Components\ToggleButtons;

class StatusToggleButtons
{
    public static function make()
    {
        return ToggleButtons::make('status')
            ->label('Status')
            ->options(status_options())
            ->colors(status_options_colors())
            ->inline();
    }
}
