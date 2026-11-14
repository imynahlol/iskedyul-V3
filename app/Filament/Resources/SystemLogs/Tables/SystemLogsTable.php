<?php

namespace App\Filament\Resources\SystemLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SystemLogsTable {
    public static function configure(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Log ID')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Performed By')
                    ->searchable()
                    ->placeholder('System'),

                TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'created', 'login' => 'success',
                        'updated' => 'info',
                        'deleted', 'logout' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('module')
                    ->label('Module')
                    ->searchable()
                    ->placeholder('N/A'),

                TextColumn::make('description')
                    ->label('Activity Details')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->searchable(),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Date and Time')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('action')
                    ->options([
                        'Created' => 'Created',
                        'Updated' => 'Updated',
                        'Deleted' => 'Deleted',
                        'Login' => 'Login',
                        'Logout' => 'Logout',
                    ]),

                SelectFilter::make('module')
                    ->options(fn () => \App\Models\SystemLog::query()
                        ->whereNotNull('module')
                        ->distinct()
                        ->pluck('module', 'module')
                        ->toArray()),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
    }
}