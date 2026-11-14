<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ReportsOverview;
use App\Models\Reservation;
use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use App\Exports\ReservationReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        $counts = Reservation::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $reserved = (int) ($counts['Reserved'] ?? 0);
        $cancelled = (int) ($counts['Cancelled'] ?? 0);
        $completed = (int) ($counts['Completed'] ?? 0);

        return [
            'reserved' => $reserved,
            'cancelled' => $cancelled,
            'completed' => $completed,

            'reservedPercent' => $total > 0 ? ($reserved / $total) * 100 : 0,
            'cancelledPercent' => $total > 0 ? ($cancelled / $total) * 100 : 0,
            'completedPercent' => $total > 0 ? ($completed / $total) * 100 : 0,

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

            'studentReservations' => Reservation::whereHas(
                'user',
                fn ($query) => $query->where('user_type', 'Student')
            )->count(),

            'facultyReservations' => Reservation::whereHas(
                'user',
                fn ($query) => $query->where('user_type', 'Faculty')
            )->count(),
        ];
    }

    public function exportExcel(): BinaryFileResponse {
        return Excel::download(
            new ReservationReportExport(),
            'iSKEDYUL-Reservation-Report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}