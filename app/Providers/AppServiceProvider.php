<?php

namespace App\Providers;

use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Models\Building;
use App\Models\Room;
use App\Models\Reservation;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        //
    }

    public function boot(): void {
        Event::listen(Login::class, function (Login $event) {
            SystemLog::create([
                'user_id' => $event->user->id,
                'action' => 'Login',
                'module' => 'Authentication',
                'description' => $event->user->name . ' logged in.',
                'ip_address' => request()->ip(),
            ]);
        });

        Building::created(function (Building $building) {
            SystemLog::create([
                'user_id' => auth()->id(),
                'action' => 'Created',
                'module' => 'Building Management',
                'description' => 'Created building: ' . ($building->building_name ?? 'ID ' . $building->id),
                'ip_address' => request()->ip(),
            ]);
        });

        Building::updated(function (Building $building) {
            SystemLog::create([
                'user_id' => auth()->id(),
                'action' => 'Updated',
                'module' => 'Building Management',
                'description' => 'Updated building: ' . ($building->building_name ?? 'ID ' . $building->id),
                'ip_address' => request()->ip(),
            ]);
        });

        Building::deleted(function (Building $building) {
            SystemLog::create([
                'user_id' => auth()->id(),
                'action' => 'Deleted',
                'module' => 'Building Management',
                'description' => 'Deleted building: ' . ($building->building_name ?? 'ID ' . $building->id),
                'ip_address' => request()->ip(),
            ]);
        });

        Room::created(function (Room $room) {
            SystemLog::create([
                'user_id' => auth()->id(),
                'action' => 'Created',
                'module' => 'Room Management',
                'description' => 'Created room: ' . ($room->room_code ?? 'ID ' . $room->id),
                'ip_address' => request()->ip(),
            ]);
        });

        Room::updated(function (Room $room) {
            SystemLog::create([
                'user_id' => auth()->id(),
                'action' => 'Updated',
                'module' => 'Room Management',
                'description' => 'Updated room: ' . ($room->room_code ?? 'ID ' . $room->id),
                'ip_address' => request()->ip(),
            ]);
        });

        Room::deleted(function (Room $room) {
            SystemLog::create([
                'user_id' => auth()->id(),
                'action' => 'Deleted',
                'module' => 'Room Management',
                'description' => 'Deleted room: ' . ($room->room_code ?? 'ID ' . $room->id),
                'ip_address' => request()->ip(),
            ]);
        });


        Reservation::created(function (Reservation $reservation) {
            SystemLog::create([
                'user_id' => auth()->id() ?? $reservation->user_id,
                'action' => 'Created',
                'module' => 'Reservation Management',
                'description' => 'Created reservation ' . $reservation->reservation_number . '.',
                'ip_address' => request()->ip(),
            ]);
        });

        Reservation::updated(function (Reservation $reservation) {
            $action = $reservation->wasChanged('status')
                && $reservation->status === 'Cancelled'
                ? 'Cancelled'
                : 'Updated';

            SystemLog::create([
                'user_id' => auth()->id() ?? $reservation->user_id,
                'action' => $action,
                'module' => 'Reservation Management',
                'description' => $action . ' reservation ' . $reservation->reservation_number . '.',
                'ip_address' => request()->ip(),
            ]);
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                SystemLog::create([
                    'user_id' => $event->user->id,
                    'action' => 'Logout',
                    'module' => 'Authentication',
                    'description' => $event->user->name . ' logged out.',
                    'ip_address' => request()->ip(),
                ]);
            }
        });

        User::created(function (User $user) {
            SystemLog::create([
                'user_id' => auth()->id(),
                'action' => 'Created',
                'module' => 'User Management',
                'description' => 'Created account for ' . $user->name . ' (' . $user->user_id . ').',
                'ip_address' => request()->ip(),
            ]);
        });

        User::updated(function (User $user) {
            SystemLog::create([
                'user_id' => auth()->id(),
                'action' => 'Updated',
                'module' => 'User Management',
                'description' => 'Updated account for ' . $user->name . ' (' . $user->user_id . ').',
                'ip_address' => request()->ip(),
            ]);
        });

        User::deleted(function (User $user) {
            SystemLog::create([
                'user_id' => auth()->id(),
                'action' => 'Deleted',
                'module' => 'User Management',
                'description' => 'Deleted account for ' . $user->name . ' (' . $user->user_id . ').',
                'ip_address' => request()->ip(),
            ]);
        });
    }
}