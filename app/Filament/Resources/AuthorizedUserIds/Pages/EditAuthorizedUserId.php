<?php

namespace App\Filament\Resources\AuthorizedUserIds\Pages;

use App\Filament\Resources\AuthorizedUserIds\AuthorizedUserIdResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthorizedUserId extends EditRecord
{
    protected static string $resource = AuthorizedUserIdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
