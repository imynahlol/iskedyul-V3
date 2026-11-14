<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable {
    public static function configure(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user_id')
                    ->label('Official ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->placeholder('No email'),

                TextColumn::make('user_type')
                    ->label('Account Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Admin' => 'danger',
                        'Faculty' => 'warning',
                        'Student' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('program')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('year_level')
                    ->label('Year')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('section')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_type')
                    ->label('Account Type')
                    ->options([
                        'Admin' => 'Administrator',
                        'Student' => 'Student',
                        'Faculty' => 'Faculty',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),

                DeleteAction::make()
                    ->visible(fn ($record) => $record->user_type !== 'Admin'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}