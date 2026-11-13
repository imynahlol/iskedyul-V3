<?php

namespace App\Filament\Resources\ReservationLogs\Pages;

use App\Filament\Resources\ReservationLogs\ReservationLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReservationLog extends CreateRecord
{
    protected static string $resource = ReservationLogResource::class;
}
