<?php

namespace App\Filament\Resources\ReservationLogs\Pages;

use App\Filament\Resources\ReservationLogs\ReservationLogResource;
use Filament\Resources\Pages\ListRecords;

class ListReservationLogs extends ListRecords {
    protected static string $resource = ReservationLogResource::class;

    protected function getHeaderActions(): array {
        return [];
    }
}