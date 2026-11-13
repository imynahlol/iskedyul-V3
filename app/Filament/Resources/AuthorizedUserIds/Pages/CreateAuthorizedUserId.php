<?php

namespace App\Filament\Resources\AuthorizedUserIds\Pages;

use App\Filament\Resources\AuthorizedUserIds\AuthorizedUserIdResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthorizedUserId extends CreateRecord
{
    protected static string $resource = AuthorizedUserIdResource::class;
}
