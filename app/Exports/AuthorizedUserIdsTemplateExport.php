<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuthorizedUserIdsTemplateExport implements FromCollection, WithHeadings, WithStyles {
    public function collection(): Collection {
        return collect([
            [
                '2026-12345-LQ-1',
                'Student',
            ],
            [
                '12345',
                'Faculty',
            ],
        ]);
    }

    public function headings(): array {
        return [
            'user_id',
            'user_type',
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
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '800000'],
                ],
            ],
        ];
    }
}