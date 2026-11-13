<?php

namespace App\Filament\Portal\Pages;

use App\Models\Reservation;
use Filament\Pages\Page;

class Dashboard extends Page {
    protected string $view = 'filament.portal.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    public function getViewData(): array {
        $userId = auth()->id();

        return [
            'totalReservations' => Reservation::where('user_id', $userId)->count(),
            'activeReservations' => Reservation::where('user_id', $userId)
                ->where('status', 'Reserved')
                ->count(),
            'cancelledReservations' => Reservation::where('user_id', $userId)
                ->where('status', 'Cancelled')
                ->count(),
            'upcomingReservations' => Reservation::where('user_id', $userId)
                ->where('status', 'Reserved')
                ->whereDate('reservation_date', '>=', now()->toDateString())
                ->count(),
            'recentReservations' => Reservation::with(['building', 'room'])
                ->where('user_id', $userId)
                ->latest()
                ->take(5)
                ->get(),
        ];
    }
}