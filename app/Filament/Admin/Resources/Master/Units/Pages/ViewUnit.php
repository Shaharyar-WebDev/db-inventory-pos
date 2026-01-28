<?php

namespace App\Filament\Admin\Resources\Master\Units\Pages;

use App\Filament\Admin\Resources\Master\Units\UnitResource;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUnit extends ViewRecord
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
            EditAction::make(),
        ];
    }
}
