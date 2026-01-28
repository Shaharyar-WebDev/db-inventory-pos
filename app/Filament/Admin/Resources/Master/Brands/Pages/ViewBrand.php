<?php

namespace App\Filament\Admin\Resources\Master\Brands\Pages;

use App\Filament\Admin\Resources\Master\Brands\BrandResource;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBrand extends ViewRecord
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
            EditAction::make(),
        ];
    }
}
