<?php

namespace App\Filament\Admin\Resources\Master\Groups;

use App\Filament\Admin\Resources\Master\Groups\Pages\CreateGroup;
use App\Filament\Admin\Resources\Master\Groups\Pages\EditGroup;
use App\Filament\Admin\Resources\Master\Groups\Pages\ListGroups;
use App\Filament\Admin\Resources\Master\Groups\Pages\ViewGroup;
use App\Filament\Admin\Resources\Master\Groups\RelationManagers\ProductsRelationManager;
use App\Filament\Admin\Resources\Master\Groups\Schemas\GroupForm;
use App\Filament\Admin\Resources\Master\Groups\Schemas\GroupInfolist;
use App\Filament\Admin\Resources\Master\Groups\Tables\GroupsTable;
use App\Models\Master\Group;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return GroupForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GroupInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GroupsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGroups::route('/'),
            'create' => CreateGroup::route('/create'),
            'view' => ViewGroup::route('/{record}'),
            'edit' => EditGroup::route('/{record}/edit'),
        ];
    }
}
