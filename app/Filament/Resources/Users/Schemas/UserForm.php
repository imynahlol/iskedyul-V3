<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm {
    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('user_id')
                    ->label('Official Student / Faculty ID')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20)
                    ->placeholder('2025-00123-LQ-1'),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->nullable(),

                Select::make('user_type')
                    ->label('Account Type')
                    ->options([
                        'Student' => 'Student',
                        'Faculty' => 'Faculty',
                        'Admin' => 'Administrator',
                    ])
                    ->default('Student')
                    ->required()
                    ->live()
                    ->disabled(fn ($record) => $record?->user_type === 'Admin'),

                TextInput::make('program')
                    ->label('Program')
                    ->visible(fn ($get) => $get('user_type') === 'Student')
                    ->nullable(),

                Select::make('year_level')
                    ->label('Year Level')
                    ->options([
                        '1' => '1st Year',
                        '2' => '2nd Year',
                        '3' => '3rd Year',
                        '4' => '4th Year',
                    ])
                    ->visible(fn ($get) => $get('user_type') === 'Student')
                    ->nullable(),

                TextInput::make('section')
                    ->label('Section')
                    ->visible(fn ($get) => $get('user_type') === 'Student')
                    ->nullable(),
                                
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation) => $operation === 'create')
                    ->minLength(8)
                    ->maxLength(255)
                    ->helperText('Leave blank when editing to keep the current password.'),
            ]);
    }
}