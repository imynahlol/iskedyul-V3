<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReservationReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles {
    public function collection(): Collection {
        return Reservation::with(['user', 'building', 'room'])
            ->latest()
            ->get()
            ->map(fn ($reservation) => [
                $reservation->reservation_number,
                $reservation->user?->name ?? 'Unknown',
                $reservation->user?->user_id ?? 'N/A',
                $reservation->building?->building_name ?? 'Unknown',
                $reservation->room?->room_code ?? 'Unknown',
                $reservation->reservation_date
                    ? Carbon::parse($reservation->reservation_date)->format('Y-m-d')
                    : null,
                $reservation->start_time
                    ? Carbon::parse($reservation->start_time)->format('h:i A')
                    : null,
                $reservation->end_time
                    ? Carbon::parse($reservation->end_time)->format('h:i A')
                    : null,
                $reservation->purpose,
                $reservation->status,
                $reservation->created_at?->format('M d, Y h:i A'),
            ]);
    }

    public function headings(): array {
        return [
            'Reservation Number',
            'Reserved By',
            'User ID',
            'Building',
            'Room',
            'Reservation Date',
            'Start Time',
            'End Time',
            'Purpose',
            'Status',
            'Date Created',
        ];
    }

    public function styles(Worksheet $sheet): array {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '7B1E2B'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}