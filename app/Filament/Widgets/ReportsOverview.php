<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReportsOverview extends BaseWidget {
    protected function getStats(): array {
        return [
            Stat::make('Total Reservations', Reservation::count())
                ->description('All recorded bookings')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Reserved', Reservation::where('status', 'Reserved')->count())
                ->description('Active reservation records')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Cancelled', Reservation::where('status', 'Cancelled')->count())
                ->description('Cancelled booking records')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make("Today's Reservations", Reservation::whereDate('reservation_date', today())->where('status', 'Reserved')->count())
                ->description('Bookings scheduled for today')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Students', User::where('user_type', 'Student')->count())
                ->description('Registered student accounts')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),

            Stat::make('Faculty', User::where('user_type', 'Faculty')->count())
                ->description('Registered faculty accounts')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}