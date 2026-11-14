<?php

namespace App\Filament\Resources\Reservations\Pages;

use App\Filament\Resources\Reservations\ReservationResource;
use App\Services\ReservationService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReservation extends CreateRecord {
    protected static string $resource = ReservationResource::class;

    protected function handleRecordCreation(array $data): Model {
        return app(ReservationService::class)->create(
            $data,
            (int) $data['user_id'],
            (int) auth()->id()
        );
    }
}