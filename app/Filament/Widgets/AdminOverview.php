<?php

namespace App\Filament\Widgets;

use App\Models\Building;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverview extends BaseWidget {
    protected function getStats(): array {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered students, faculty, and administrators')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Buildings', Building::count())
                ->description('Campus buildings')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),

            Stat::make('Classrooms', Room::count())
                ->description('Registered rooms and laboratories')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Active Reservations', Reservation::where('status', 'Reserved')->count())
                ->description('Currently active bookings')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),

            Stat::make("Today's Reservations", Reservation::whereDate('reservation_date', today())->where('status', 'Reserved')->count())
                ->description('Bookings scheduled for today')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Cancelled Reservations', Reservation::where('status', 'Cancelled')->count())
                ->description('Total cancelled bookings')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}