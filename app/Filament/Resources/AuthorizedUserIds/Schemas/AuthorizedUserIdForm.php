<?php

namespace App\Filament\Resources\AuthorizedUserIds\Schemas;

use App\Models\AuthorizedUserId;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AuthorizedUserIdForm {
    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                Select::make('user_type')
                    ->label('Account Type')
                    ->options([
                        'Student' => 'Student',
                        'Faculty' => 'Faculty',
                    ])
                    ->required()
                    ->live()
                    ->disabled(fn (?AuthorizedUserId $record): bool => $record?->claimed_by !== null),

                TextInput::make('user_id')
                    ->label('Official ID Number')
                    ->placeholder('20XX-XXXXX-LQ-X or 5-digit Faculty ID')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true)
                    ->rules(fn (Get $get): array => match ($get('user_type')) {
                        'Student' => ['regex:/^20\d{2}-\d{5}-LQ-\d$/'],
                        'Faculty' => ['regex:/^\d{5}$/'],
                        default => [],
                    })
                    ->validationMessages([
                        'regex' => 'The ID format does not match the selected account type.',
                        'unique' => 'This ID already exists in the authorized ID records.',
                    ])
                    ->disabled(fn (?AuthorizedUserId $record): bool => $record?->claimed_by !== null),

                Toggle::make('is_active')
                    ->label('Active Status')
                    ->helperText('Inactive IDs cannot be used for registration.')
                    ->default(true)
                    ->required()
                    ->disabled(fn (?AuthorizedUserId $record): bool => $record?->claimed_by !== null),
            ]);
    }
}