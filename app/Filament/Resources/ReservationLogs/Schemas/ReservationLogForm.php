<?php

namespace App\Filament\Resources\ReservationLogs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReservationLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reservation_id')
                    ->relationship('reservation', 'id')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('action')
                    ->required(),
                Textarea::make('previous_values')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('new_values')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
