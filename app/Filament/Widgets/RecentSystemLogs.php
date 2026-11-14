<?php

namespace App\Filament\Widgets;

use App\Models\SystemLog;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentSystemLogs extends TableWidget {
    protected static ?string $heading = 'Recent System Activities';

    protected static ?int $sort = 3;

    public function table(Table $table): Table {
        return $table
            ->query(fn (): Builder => SystemLog::query()
                ->with('user')
                ->latest()
                ->limit(10)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Performed By')
                    ->placeholder('System')
                    ->searchable(),

                TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'created', 'login' => 'success',
                        'updated' => 'info',
                        'deleted', 'logout', 'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('module')
                    ->label('Module')
                    ->placeholder('N/A'),

                TextColumn::make('description')
                    ->label('Activity Details')
                    ->limit(45)
                    ->tooltip(fn ($record) => $record->description),

                TextColumn::make('created_at')
                    ->label('Date and Time')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->paginated(false)
            ->recordActions([])
            ->toolbarActions([]);
    }
}