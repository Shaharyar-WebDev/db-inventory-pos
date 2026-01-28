<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->unique(ignorable: fn($record) => $record)
                            ->required(),
                        // DateTimePicker::make('email_verified_at'),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->columnSpanFull()
                            ->disabled(function ($record) {
                                $isSuperAdmin = $record?->roles->contains('name', 'super-admin');
                                $isCurrentUser = $record?->id === filament()->auth()->user()->id;
                                return $isSuperAdmin || $isCurrentUser;
                            })
                            ->preload(),
                        Select::make('outlets')
                            ->relationship('outlets', 'name')
                            ->multiple()
                            ->columnSpanFull()
                            ->disabled(function ($record) {
                                $isSuperAdmin = $record?->roles->contains('name', 'super-admin');
                                $isCurrentUser = $record?->id === filament()->auth()->user()->id;
                                return $isSuperAdmin || $isCurrentUser;
                            })
                            ->preload(),
                        TextInput::make('password')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required(fn($record) => !$record)
                            ->rule(Password::default())
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->same('passwordConfirmation'),

                        TextInput::make('passwordConfirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required(fn($record) => !$record)
                            ->dehydrated(false)
                    ]),
            ]);
    }
}
