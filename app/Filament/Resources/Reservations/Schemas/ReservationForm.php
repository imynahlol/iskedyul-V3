<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Models\Building;
use App\Models\Room;
use App\Models\SystemSetting;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class ReservationForm {
    public static function configure(Schema $schema): Schema {
        $settings = SystemSetting::whereIn('key', [
            'min_duration',
            'max_duration',
            'advance_days',
            'opening_time',
            'closing_time',
        ])->pluck('value', 'key');

        $minDuration = (float) ($settings['min_duration'] ?? 1);
        $maxDuration = (float) ($settings['max_duration'] ?? 4);
        $advanceDays = (int) ($settings['advance_days'] ?? 30);
        $openingTime = substr($settings['opening_time'] ?? '07:00', 0, 5);
        $closingTime = substr($settings['closing_time'] ?? '21:00', 0, 5);

        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Reserved By')
                    ->options(fn () => User::whereIn('user_type', ['Student', 'Faculty'])
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->rules([
                        Rule::exists('users', 'id')
                            ->whereIn('user_type', ['Student', 'Faculty']),
                    ]),

                Select::make('building_id')
                    ->label('Building')
                    ->options(fn () => Building::where('status', 'Active')
                        ->orderBy('building_name')
                        ->pluck('building_name', 'id'))
                    ->searchable()
                    ->live()
                    ->required()
                    ->rules([
                        Rule::exists('buildings', 'id')
                            ->where('status', 'Active'),
                    ]),

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
                    ->disabled(fn (Get $get) => ! $get('building_id'))
                    ->rules([
                        fn (Get $get) => Rule::exists('rooms', 'id')
                            ->where('building_id', $get('building_id'))
                            ->where('status', 'Active'),
                    ]),

                DatePicker::make('reservation_date')
                    ->label('Reservation Date')
                    ->minDate(now()->startOfDay())
                    ->maxDate(now()->addDays($advanceDays))
                    ->displayFormat('m/d/Y')
                    ->native(false)
                    ->required(),

                TimePicker::make('start_time')
                    ->label('Start Time')
                    ->seconds(false)
                    ->native(false)
                    ->required()
                    ->rules([
                        function (string $attribute, $value, $fail) use ($openingTime, $closingTime) {
                            $time = substr((string) $value, 0, 5);

                            if ($time < $openingTime || $time >= $closingTime) {
                                $fail("Start time must be between {$openingTime} and before {$closingTime}.");
                            }
                        },
                    ]),

                TimePicker::make('end_time')
                    ->label('End Time')
                    ->seconds(false)
                    ->native(false)
                    ->required()
                    ->rules([
                        function (string $attribute, $value, $fail) use ($openingTime, $closingTime) {
                            $time = substr((string) $value, 0, 5);

                            if ($time <= $openingTime || $time > $closingTime) {
                                $fail("End time must be after {$openingTime} and no later than {$closingTime}.");
                            }
                        },
                    ]),

                Textarea::make('purpose')
                    ->label('Purpose of Reservation')
                    ->placeholder('Explain why this room is needed...')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}