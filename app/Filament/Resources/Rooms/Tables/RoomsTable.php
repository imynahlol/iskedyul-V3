<?php

namespace App\Filament\Resources\Rooms\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RoomsTable {
    public static function configure(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('room_code')
                    ->label('Room Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room_name')
                    ->label('Room Name')
                    ->searchable()
                    ->placeholder('Regular Classroom')
                    ->sortable(),

                TextColumn::make('building.building_name')
                    ->label('Building')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Active' ? 'success' : 'danger')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('building')
                    ->relationship('building', 'building_name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}