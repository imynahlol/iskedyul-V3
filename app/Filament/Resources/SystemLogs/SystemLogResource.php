<?php

namespace App\Filament\Resources\SystemLogs;

use App\Filament\Resources\SystemLogs\Pages\ListSystemLogs;
use App\Filament\Resources\SystemLogs\Tables\SystemLogsTable;
use App\Models\SystemLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SystemLogResource extends Resource {
    protected static ?string $model = SystemLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'action';

    protected static ?string $navigationLabel = 'System Logs';

    protected static ?string $modelLabel = 'System Log';

    protected static ?string $pluralModelLabel = 'System Logs';

    protected static string|\UnitEnum|null $navigationGroup = 'System Management';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema {
        return $schema;
    }

    public static function table(Table $table): Table {
        return SystemLogsTable::configure($table);
    }

    public static function getPages(): array {
        return [
            'index' => ListSystemLogs::route('/'),
        ];
    }
}