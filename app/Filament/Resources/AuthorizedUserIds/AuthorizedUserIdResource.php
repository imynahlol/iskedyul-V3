<?php

namespace App\Filament\Resources\AuthorizedUserIds;

use App\Filament\Resources\AuthorizedUserIds\Pages\CreateAuthorizedUserId;
use App\Filament\Resources\AuthorizedUserIds\Pages\EditAuthorizedUserId;
use App\Filament\Resources\AuthorizedUserIds\Pages\ListAuthorizedUserIds;
use App\Filament\Resources\AuthorizedUserIds\Schemas\AuthorizedUserIdForm;
use App\Filament\Resources\AuthorizedUserIds\Tables\AuthorizedUserIdsTable;
use App\Models\AuthorizedUserId;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuthorizedUserIdResource extends Resource {
    protected static ?string $model = AuthorizedUserId::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $recordTitleAttribute = 'user_id';

    protected static ?string $navigationLabel = 'Authorized IDs';

    protected static ?string $modelLabel = 'Authorized ID';

    protected static ?string $pluralModelLabel = 'Authorized IDs';

    protected static string|\UnitEnum|null $navigationGroup = 'System Management';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema {
        return AuthorizedUserIdForm::configure($schema);
    }

    public static function table(Table $table): Table {
        return AuthorizedUserIdsTable::configure($table);
    }

    public static function getPages(): array {
        return [
            'index' => ListAuthorizedUserIds::route('/'),
            'create' => CreateAuthorizedUserId::route('/create'),
            'edit' => EditAuthorizedUserId::route('/{record}/edit'),
        ];
    }
}