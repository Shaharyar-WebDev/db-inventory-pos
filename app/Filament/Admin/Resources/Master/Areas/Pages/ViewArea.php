<?php

namespace App\Filament\Admin\Resources\Master\Areas\Pages;

use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Admin\Resources\Master\Areas\AreaResource;

class ViewArea extends ViewRecord
{
    protected static string $resource = AreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
           RestoreAction::make(),
           ForceDeleteAction::make(),
           EditAction::make(),
        ];
    }
}
