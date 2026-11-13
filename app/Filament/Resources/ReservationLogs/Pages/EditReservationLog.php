<?php

namespace App\Filament\Resources\ReservationLogs\Pages;

use App\Filament\Resources\ReservationLogs\ReservationLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReservationLog extends EditRecord
{
    protected static string $resource = ReservationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
