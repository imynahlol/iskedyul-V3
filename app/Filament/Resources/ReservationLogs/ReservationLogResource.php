<?php

namespace App\Filament\Resources\ReservationLogs;

use App\Filament\Resources\ReservationLogs\Pages\ListReservationLogs;
use App\Filament\Resources\ReservationLogs\Tables\ReservationLogsTable;
use App\Models\ReservationLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReservationLogResource extends Resource {
    protected static ?string $model = ReservationLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'action';

    protected static ?string $navigationLabel = 'Reservation Logs';

    protected static string|\UnitEnum|null $navigationGroup = 'System Management';

    public static function form(Schema $schema): Schema {
        return $schema;
    }

    public static function table(Table $table): Table {
        return ReservationLogsTable::configure($table);
    }

    public static function getPages(): array {
        return [
            'index' => ListReservationLogs::route('/'),
        ];
    }
}