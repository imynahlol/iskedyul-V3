<?php

namespace App\Filament\Resources\Buildings\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoomsRelationManager extends RelationManager {
    protected static string $relationship = 'rooms';

    public function form(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make('room_code')
                    ->label('Room Code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),

                TextInput::make('room_name')
                    ->label('Room Name')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Room Status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ])
                    ->default('Active')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('room_code')
            ->columns([
                TextColumn::make('room_code')
                    ->label('Room Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room_name')
                    ->label('Room Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(40)
                    ->placeholder('No description'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                Action::make('printRooms')
                    ->label('Print / Save PDF')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (): string => route('admin.buildings.rooms.print', [
                        'building' => $this->getOwnerRecord()->getKey(),
                    ]))
                    ->openUrlInNewTab(),

                CreateAction::make()
                    ->label('Add Room'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}