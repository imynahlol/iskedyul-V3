<?php

namespace App\Filament\Resources\Reservations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReservationInfolist {
    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                TextEntry::make('reservation_number')
                    ->label('Reservation Number')
                    ->copyable(),

                TextEntry::make('user.name')
                    ->label('Reserved By'),

                TextEntry::make('user.user_id')
                    ->label('Official ID'),

                TextEntry::make('building.building_name')
                    ->label('Building'),

                TextEntry::make('room.room_code')
                    ->label('Room'),

                TextEntry::make('reservation_date')
                    ->date('F d, Y'),

                TextEntry::make('start_time')
                    ->time('g:i A'),

                TextEntry::make('end_time')
                    ->time('g:i A'),

                TextEntry::make('duration_hours')
                    ->label('Duration')
                    ->suffix(' hours'),

                TextEntry::make('purpose')
                    ->columnSpanFull(),

                TextEntry::make('status')
                    ->badge(),

                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }
}