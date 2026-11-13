<?php

namespace App\Filament\Resources\Rooms\Schemas;

use App\Models\Building;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RoomForm {
    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                Select::make('building_id')
                    ->label('Building')
                    ->relationship(
                        name: 'building',
                        titleAttribute: 'building_name',
                        modifyQueryUsing: fn ($query) => $query->orderBy('building_name')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('room_code')
                    ->label('Room Code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),

                TextInput::make('room_name')
                    ->label('Room Name / Laboratory Name')
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Room Status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ])
                    ->default('Active')
                    ->required(),
            ]);
    }
}