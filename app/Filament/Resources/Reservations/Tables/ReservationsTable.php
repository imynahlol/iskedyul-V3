<?php

namespace App\Filament\Resources\Reservations\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReservationsTable {
    public static function configure(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('reservation_number')
                    ->label('Reservation No.')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('user.name')
                    ->label('Reserved By')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('building.building_name')
                    ->label('Building')
                    ->searchable(),

                TextColumn::make('room.room_code')
                    ->label('Room')
                    ->searchable(),

                TextColumn::make('reservation_date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('start_time')
                    ->time('g:i A'),

                TextColumn::make('end_time')
                    ->time('g:i A'),

                TextColumn::make('duration_hours')
                    ->label('Duration')
                    ->suffix(' hrs'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Reserved' => 'success',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Reserved' => 'Reserved',
                        'Cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('building')
                    ->relationship('building', 'building_name'),
            ])            
            ->recordActions([
                ViewAction::make(),

                EditAction::make()
                    ->visible(fn ($record) => $record->status === 'Reserved'),

                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Reservation')
                    ->modalDescription('Are you sure you want to cancel this reservation? The record will be preserved in the system.')
                    ->visible(fn ($record) => $record->status === 'Reserved')
                    ->action(fn ($record) => app(\App\Services\ReservationService::class)
                        ->cancel($record, (int) auth()->id())),
            ])

            ->defaultSort('reservation_date', 'desc');
    }
}