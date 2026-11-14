<?php

namespace App\Filament\Resources\AuthorizedUserIds\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AuthorizedUserIdsTable {
    public static function configure(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('Official ID')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('user_type')
                    ->label('Account Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Student' => 'info',
                        'Faculty' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('claimedBy.name')
                    ->label('Registered Account')
                    ->placeholder('Not yet claimed')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('claimed_at')
                    ->label('Claimed At')
                    ->dateTime('M d, Y h:i A')
                    ->placeholder('Not yet claimed')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date Added')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_type')
                    ->label('Account Type')
                    ->options([
                        'Student' => 'Student',
                        'Faculty' => 'Faculty',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Active Status'),

                TernaryFilter::make('claimed_by')
                    ->label('Claim Status')
                    ->nullable()
                    ->trueLabel('Claimed')
                    ->falseLabel('Available'),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record): bool => $record->claimed_by === null),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}