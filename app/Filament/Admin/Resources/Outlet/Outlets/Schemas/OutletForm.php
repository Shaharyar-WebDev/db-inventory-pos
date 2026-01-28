<?php

namespace App\Filament\Admin\Resources\Outlet\Outlets\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                                Toggle::make('is_active')
                                    ->default(true)
                                    ->required(),
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
