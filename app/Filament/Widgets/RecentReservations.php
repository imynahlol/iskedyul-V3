<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentReservations extends BaseWidget {
    protected static ?string $heading = 'Recent Reservations';

    protected static ?int $sort = 2;

    public function table(Table $table): Table {
        return $table
            ->query(
                Reservation::query()
                    ->with(['user', 'building', 'room'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('reservation_number')
                    ->label('Reservation No.')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Reserved By')
                    ->searchable(),

                TextColumn::make('building.building_name')
                    ->label('Building'),

                TextColumn::make('room.room_code')
                    ->label('Room'),

                TextColumn::make('reservation_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Start')
                    ->time('h:i A'),

                TextColumn::make('end_time')
                    ->label('End')
                    ->time('h:i A'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Reserved' => 'success',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}