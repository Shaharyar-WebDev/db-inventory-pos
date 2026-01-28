<?php

namespace App\Filament\Admin\Resources\Master\Categories\Pages;

use App\Filament\Admin\Resources\Master\Categories\CategoryResource;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCategory extends ViewRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
            EditAction::make(),
        ];
    }
}
