<?php

namespace App\Filament\Admin\Resources\Outlet\Outlets;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Outlet\Outlet;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Models\Traits\IgnoresSoftDeleteRouteBinding;
use App\Filament\Admin\Resources\Outlet\Outlets\Pages\EditOutlet;
use App\Filament\Admin\Resources\Outlet\Outlets\Pages\ViewOutlet;
use App\Filament\Admin\Resources\Outlet\Outlets\Pages\ListOutlets;
use App\Filament\Admin\Resources\Outlet\Outlets\Pages\CreateOutlet;
use App\Filament\Admin\Resources\Outlet\Outlets\Schemas\OutletForm;
use App\Filament\Admin\Resources\Outlet\Outlets\Tables\OutletsTable;
use App\Filament\Admin\Resources\Outlet\Outlets\Schemas\OutletInfolist;
use App\Filament\Admin\Resources\Outlet\Outlets\RelationManagers\UsersRelationManager;

class OutletResource extends Resource
{
    use IgnoresSoftDeleteRouteBinding;
    protected static ?string $model = Outlet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::HomeModern;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return OutletForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OutletInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OutletsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOutlets::route('/'),
            'create' => CreateOutlet::route('/create'),
            // 'view' => ViewOutlet::route('/{record}'),
            'edit' => EditOutlet::route('/{record}/edit'),
        ];
    }


}
