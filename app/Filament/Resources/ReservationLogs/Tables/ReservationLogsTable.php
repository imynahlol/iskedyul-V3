<?php

namespace App\Filament\Resources\ReservationLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReservationLogsTable {
    public static function configure(Table $table): Table {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reservation.reservation_number')
                    ->label('Reservation Number')
                    ->searchable()
                    ->placeholder('N/A'),

                TextColumn::make('user.name')
                    ->label('Performed By')
                    ->searchable()
                    ->placeholder('Unknown User'),

                TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Created' => 'success',
                        'Updated' => 'warning',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Date and Time')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),

                TextColumn::make('previous_values')
                    ->label('Previous Values')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('new_values')
                    ->label('New Values')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('action')
                    ->options([
                        'Created' => 'Created',
                        'Updated' => 'Updated',
                        'Cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}