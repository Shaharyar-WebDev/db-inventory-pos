<?php

namespace App\Filament\Admin\Resources\Master\Cities;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\Master\City;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\IgnoresSoftDeleteRouteBinding;
use App\Filament\Admin\Resources\Master\Cities\Pages\EditCity;
use App\Filament\Admin\Resources\Master\Cities\Pages\ViewCity;
use App\Filament\Admin\Resources\Master\Cities\Pages\CreateCity;
use App\Filament\Admin\Resources\Master\Cities\Pages\ListCities;
use App\Filament\Admin\Resources\Master\Cities\Schemas\CityForm;
use App\Filament\Admin\Resources\Master\Cities\Tables\CitiesTable;
use App\Filament\Admin\Resources\Master\Cities\Schemas\CityInfolist;

class CityResource extends Resource
{
    use IgnoresSoftDeleteRouteBinding;
    protected static ?string $model = City::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CitiesTable::configure($table);
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
            'index' => ListCities::route('/'),
            // 'create' => CreateCity::route('/create'),
            // 'view' => ViewCity::route('/{record}'),
            // 'edit' => EditCity::route('/{record}/edit'),
        ];
    }



}
