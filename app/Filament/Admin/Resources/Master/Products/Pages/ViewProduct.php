<?php

namespace App\Filament\Admin\Resources\Master\Products\Pages;

use App\Filament\Admin\Resources\Master\Products\ProductResource;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
            EditAction::make(),
        ];
    }
}
