<?php

namespace App\Filament\Resources\ReservationLogs\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class ReservationLogsTable {
    public static function configure(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Log ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reservation.reservation_number')
                    ->label('Reservation Number')
                    ->searchable()
                    ->placeholder('N/A'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Performed By')
                    ->searchable()
                    ->placeholder('System'),

                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'created' => 'success',
                        'updated' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date and Time')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
    }
}