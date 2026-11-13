<?php

namespace App\Filament\Resources\Reservations\Pages;

use App\Filament\Resources\Reservations\ReservationResource;
use App\Services\ReservationService;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditReservation extends EditRecord {
    protected static string $resource = ReservationResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model {
        return app(ReservationService::class)->update(
            $record,
            $data,
            (int) auth()->id()
        );
    }

    protected function getHeaderActions(): array {
        return [
            ViewAction::make(),
        ];
    }
}