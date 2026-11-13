<?php

namespace App\Filament\Resources\Reservations\Pages;

use App\Filament\Resources\Reservations\ReservationResource;
use App\Services\ReservationService;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord {
    protected static string $resource = ReservationResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model {
        return app(ReservationService::class)->create(
            $data,
            (int) $data['user_id']
        );
    }
}