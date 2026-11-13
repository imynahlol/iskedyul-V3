<?php

namespace App\Filament\Resources\Buildings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BuildingForm {
    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make('building_name')
                    ->label('Building Name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('building_code')
                    ->label('Building Code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Building Status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ])
                    ->default('Active')
                    ->required(),
            ]);
    }
}