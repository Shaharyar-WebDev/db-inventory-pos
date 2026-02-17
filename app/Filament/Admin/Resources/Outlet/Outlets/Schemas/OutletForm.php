<?php

namespace App\Filament\Admin\Resources\Outlet\Outlets\Schemas;

use App\Enums\Status;
use App\Support\Components\StatusToggleButtons;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class OutletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Section::make()
                            ->columns(2)
                            ->columnSpan(2)
                            ->schema([
                                TextInput::make('name')
                                    ->unique()
                                    ->required(),
                                TextInput::make('phone_number')
                                    ->tel()
                                    ->default(null),
                                Textarea::make('address')
                                    ->default(null)
                                    ->columnSpanFull(),
                            ]),
                        Section::make()
                            ->columnSpan(1)
                            ->schema([
                                StatusToggleButtons::make(),
                                FileUpload::make('attachments')
                                    ->label('Attachments')
                                    ->multiple()
                                    ->directory('attachments/outlets')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->deleteUploadedFileUsing(function ($file) {
                                        Storage::disk('public')->delete($file);
                                    })
                                    ->nullable()
                                    ->downloadable()
                                    ->columnSpanFull()
                                    ->openable(),
                            ]),
                    ]),
            ]);
    }
}
