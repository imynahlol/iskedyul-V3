<?php

namespace App\Filament\Portal\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;

class Profile extends Page {
    protected string $view = 'filament.portal.pages.profile';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'My Profile';

    protected static ?string $title = 'My Profile';

    public string $name = '';

    public ?string $email = null;

    public string $userId = '';

    public string $userType = '';

    public ?string $program = null;

    public ?string $yearLevel = null;

    public ?string $section = null;

    public function mount(): void {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->userId = $user->user_id;
        $this->userType = $user->user_type;
        $this->program = $user->program;
        $this->yearLevel = $user->year_level;
        $this->section = $user->section;
    }

    public function saveProfile(): void {
        $this->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s.\'-]+$/u'],
            'email' => 'nullable|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email ?: null,
        ]);

        Notification::make()
            ->success()
            ->title('Profile updated successfully!')
            ->send();
    }
}