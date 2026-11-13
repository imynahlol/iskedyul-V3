<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Models\Building;
use App\Models\Room;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ReservationForm {
    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Reserved By')
                    ->options(
                        fn () => User::whereIn('user_type', ['Student', 'Faculty'])
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                Select::make('building_id')
                    ->label('Building')
                    ->options(
                        fn () => Building::where('status', 'Active')
                            ->orderBy('building_name')
                            ->pluck('building_name', 'id')
                    )
                    ->searchable()
                    ->live()
                    ->required(),

                Select::make('room_id')
                    ->label('Room')
                    ->options(fn (Get $get) => $get('building_id')
                        ? Room::where('building_id', $get('building_id'))
                            ->where('status', 'Active')
                            ->orderBy('room_code')
                            ->pluck('room_code', 'id')
                        : [])
                    ->searchable()
                    ->required()
                    ->disabled(fn (Get $get) => ! $get('building_id')),

                DatePicker::make('reservation_date')
                    ->label('Reservation Date')
                    ->minDate(now()->startOfDay())
                    ->displayFormat('m/d/Y')
                    ->native(false)
                    ->required(),

                TimePicker::make('start_time')
                    ->label('Start Time')
                    ->seconds(false)
                    ->native(false)
                    ->required(),

                TimePicker::make('end_time')
                    ->label('End Time')
                    ->seconds(false)
                    ->native(false)
                    ->required(),

                Textarea::make('purpose')
                    ->label('Purpose of Reservation')
                    ->placeholder('Explain why this room is needed...')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}