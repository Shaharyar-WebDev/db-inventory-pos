<?php

namespace App\Filament\Admin\Resources\Master\Categories\Pages;

use App\Filament\Admin\Resources\Master\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
