<?php

namespace App\Filament\Portal\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class Login extends BaseLogin {
    protected function getEmailFormComponent(): Component {
        return TextInput::make('user_id')
            ->label('Student / Faculty ID')
            ->placeholder('Enter your official ID')
            ->required()
            ->autofocus()
            ->autocomplete('username');
    }

    protected function getCredentialsFromFormData(array $data): array {
        return [
            'user_id' => $data['user_id'],
            'password' => $data['password'],
        ];
    }
}