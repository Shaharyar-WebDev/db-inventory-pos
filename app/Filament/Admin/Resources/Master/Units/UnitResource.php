<?php

namespace App\Filament\Admin\Resources\Master\Units;

use App\Filament\Admin\Resources\Master\Units\Pages\CreateUnit;
use App\Filament\Admin\Resources\Master\Units\Pages\EditUnit;
use App\Filament\Admin\Resources\Master\Units\Pages\ListUnits;
use App\Filament\Admin\Resources\Master\Units\Pages\ViewUnit;
use App\Filament\Admin\Resources\Master\Units\Schemas\UnitForm;
use App\Filament\Admin\Resources\Master\Units\Schemas\UnitInfolist;
use App\Filament\Admin\Resources\Master\Units\Tables\UnitsTable;
use App\Models\Master\Unit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return UnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitsTable::configure($table);
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
            'index' => ListUnits::route('/'),
            // 'create' => CreateUnit::route('/create'),
            // 'view' => ViewUnit::route('/{record}'),
            // 'edit' => EditUnit::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
