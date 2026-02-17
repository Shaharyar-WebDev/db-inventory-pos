<?php

namespace App\Filament\Admin\Resources\Master\Areas;

use App\Filament\Admin\Resources\Master\Areas\Pages\CreateArea;
use App\Filament\Admin\Resources\Master\Areas\Pages\EditArea;
use App\Filament\Admin\Resources\Master\Areas\Pages\ListAreas;
use App\Filament\Admin\Resources\Master\Areas\Pages\ViewArea;
use App\Filament\Admin\Resources\Master\Areas\Schemas\AreaForm;
use App\Filament\Admin\Resources\Master\Areas\Schemas\AreaInfolist;
use App\Filament\Admin\Resources\Master\Areas\Tables\AreasTable;
use App\Models\Master\Area;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\IgnoresSoftDeleteRouteBinding;

class AreaResource extends Resource
{
    // use IgnoresSoftDeleteRouteBinding;

    protected static ?string $model = Area::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Map;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AreaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AreaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AreasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAreas::route('/'),
            // 'create' => CreateArea::route('/create'),
            // 'view' => ViewArea::route('/{record}'),
            // 'edit' => EditArea::route('/{record}/edit'),
        ];
    }


}
