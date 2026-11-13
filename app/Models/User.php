<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser {
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'name',
        'user_type',
        'program',
        'year_level',
        'section',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool {
        if ($panel->getId() === 'admin') {
            return $this->user_type === 'Admin';
        }

        if ($panel->getId() === 'portal') {
            return in_array($this->user_type, ['Student', 'Faculty'], true);
        }

        return false;
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function reservationLogs(): HasMany {
        return $this->hasMany(ReservationLog::class);
    }

    public function authorizedUserId(): HasOne {
        return $this->hasOne(AuthorizedUserId::class, 'claimed_by');
    }
}