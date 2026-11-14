<?php

namespace App\Filament\Resources\Buildings\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BuildingsTable {
    public static function configure(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('building_name')
                    ->label('Building Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('building_code')
                    ->label('Building Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rooms_count')
                    ->label('Total Rooms')
                    ->counts('rooms')
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
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
            ])
            ->headerActions([
                Action::make('printBuildings')
                    ->label('Print / Save PDF')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (): string => route('admin.buildings.print'))
                    ->openUrlInNewTab(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}