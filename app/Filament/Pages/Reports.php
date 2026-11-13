<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ReportsOverview;
use App\Models\Reservation;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;

class Reports extends Page {
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string|UnitEnum|null $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Reports';
    protected static ?int $navigationSort = 6;

    protected string $view = 'filament.pages.reports';

    protected function getHeaderWidgets(): array {
        return [
            ReportsOverview::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array {
        return 3;
    }

    protected function getViewData(): array {
        $total = Reservation::count();

        return [
            'reserved' => Reservation::where('status', 'Reserved')->count(),
            'cancelled' => Reservation::where('status', 'Cancelled')->count(),
            'completed' => Reservation::where('status', 'Completed')->count(),

            'reservedPercent' => $total > 0 ? (Reservation::where('status', 'Reserved')->count() / $total) * 100 : 0,
            'cancelledPercent' => $total > 0 ? (Reservation::where('status', 'Cancelled')->count() / $total) * 100 : 0,
            'completedPercent' => $total > 0 ? (Reservation::where('status', 'Completed')->count() / $total) * 100 : 0,

            'buildings' => Reservation::query()
                ->selectRaw('building_id, COUNT(*) as total')
                ->with('building')
                ->groupBy('building_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),

            'rooms' => Reservation::query()
                ->selectRaw('room_id, COUNT(*) as total')
                ->with(['room', 'building'])
                ->groupBy('room_id')
                ->orderByDesc('total')
                ->limit(10)
                ->get(),

            'studentReservations' => Reservation::whereHas('user', fn ($query) => $query->where('user_type', 'Student'))->count(),

            'facultyReservations' => Reservation::whereHas('user', fn ($query) => $query->where('user_type', 'Faculty'))->count(),
        ];
    }
}