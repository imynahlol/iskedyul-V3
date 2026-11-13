<?php

namespace App\Imports;

use App\Models\AuthorizedUserId;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AuthorizedUserIdsImport implements ToCollection, WithHeadingRow {
    public int $imported = 0;
    public int $skipped = 0;
    public array $errors = [];

    public function collection(Collection $rows) {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $userId = trim((string) ($row['user_id'] ?? ''));
            $userType = trim((string) ($row['user_type'] ?? ''));

            if ($userId === '' && $userType === '') {
                continue;
            }

            $validator = Validator::make([
                'user_id' => $userId,
                'user_type' => $userType,
            ], [
                'user_id' => ['required', 'string', 'max:20'],
                'user_type' => ['required', 'in:Student,Faculty'],
            ]);

            if ($validator->fails()) {
                $this->skipped++;
                $this->errors[] = "Row {$rowNumber}: Invalid or missing ID/account type.";
                continue;
            }

            $formatIsValid = match ($userType) {
                'Student' => preg_match('/^20\d{2}-\d{5}-LQ-\d$/', $userId),
                'Faculty' => preg_match('/^\d{5}$/', $userId),
                default => false,
            };

            if (! $formatIsValid) {
                $this->skipped++;
                $this->errors[] = "Row {$rowNumber}: ID format does not match {$userType}.";
                continue;
            }

            if (AuthorizedUserId::where('user_id', $userId)->exists()) {
                $this->skipped++;
                $this->errors[] = "Row {$rowNumber}: ID {$userId} already exists.";
                continue;
            }

            AuthorizedUserId::create([
                'user_id' => $userId,
                'user_type' => $userType,
                'is_active' => true,
            ]);

            $this->imported++;
        }
    }
}