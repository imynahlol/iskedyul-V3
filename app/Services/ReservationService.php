<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservationLog;
use App\Models\Room;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservationService {
    public function create(array $data, int $userId, ?int $actorId = null): Reservation {
    $actorId = $actorId ?? $userId;

    return DB::transaction(function () use ($data, $userId, $actorId) {
        $room = Room::with('building')
            ->lockForUpdate()
            ->findOrFail($data['room_id']);

        if ($room->status !== 'Active' || $room->building->status !== 'Active') {
            throw ValidationException::withMessages([
                'room_id' => 'This room or its building is currently inactive.',
            ]);
        }

        $start = $data['start_time'];
        $end = $data['end_time'];

        $this->validateReservationRules(
            $data['reservation_date'],
            $start,
            $end
        );

        $conflict = Reservation::where('room_id', $room->id)
            ->whereDate('reservation_date', $data['reservation_date'])
            ->where('status', 'Reserved')
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'start_time' => 'This room is already reserved during the selected time.',
            ]);
        }

        $duration = (strtotime($end) - strtotime($start)) / 3600;

        $reservation = Reservation::create([
            'reservation_number' => $this->generateReservationNumber(),
            'user_id' => $userId,
            'building_id' => $room->building_id,
            'room_id' => $room->id,
            'reservation_date' => $data['reservation_date'],
            'start_time' => $start,
            'end_time' => $end,
            'purpose' => $data['purpose'],
            'status' => 'Reserved',
            'duration_hours' => $duration,
        ]);

        ReservationLog::create([
            'reservation_id' => $reservation->id,
            'user_id' => $actorId,
            'action' => 'Created',
            'previous_values' => null,
            'new_values' => $reservation->toArray(),
        ]);

        return $reservation;
    });
}

    public function update(Reservation $reservation, array $data, int $actorId): Reservation {
        return DB::transaction(function () use ($reservation, $data, $actorId) {
            $reservation = Reservation::whereKey($reservation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($reservation->status !== 'Reserved') {
                throw ValidationException::withMessages([
                    'reservation' => 'Only active reservations can be edited.',
                ]);
            }

            if ($reservation->reservation_date->isToday() || $reservation->reservation_date->isPast()) {
                throw ValidationException::withMessages([
                    'reservation_date' => 'Reservations can only be edited before their reservation date.',
                ]);
            }

            $room = Room::with('building')
                ->lockForUpdate()
                ->findOrFail($data['room_id']);

            if ($room->status !== 'Active' || $room->building->status !== 'Active') {
                throw ValidationException::withMessages([
                    'room_id' => 'This room or its building is currently inactive.',
                ]);
            }

            $start = $data['start_time'];
            $end = $data['end_time'];

            $this->validateReservationRules(
                $data['reservation_date'],
                $start,
                $end
            );

            $conflict = Reservation::where('room_id', $room->id)
                ->whereDate('reservation_date', $data['reservation_date'])
                ->where('status', 'Reserved')
                ->where('id', '!=', $reservation->id)
                ->where('start_time', '<', $end)
                ->where('end_time', '>', $start)
                ->exists();

            if ($conflict) {
                throw ValidationException::withMessages([
                    'start_time' => 'This room is already reserved during the selected time.',
                ]);
            }

            $previousValues = $reservation->toArray();

            $reservation->update([
                'building_id' => $room->building_id,
                'room_id' => $room->id,
                'reservation_date' => $data['reservation_date'],
                'start_time' => $start,
                'end_time' => $end,
                'purpose' => $data['purpose'],
                'duration_hours' => (strtotime($end) - strtotime($start)) / 3600,
            ]);

            ReservationLog::create([
                'reservation_id' => $reservation->id,
                'user_id' => $actorId,
                'action' => 'Updated',
                'previous_values' => $previousValues,
                'new_values' => $reservation->fresh()->toArray(),
            ]);

            return $reservation->fresh();
        });
    }

    private function validateReservationRules(
        string $date,
        string $start,
        string $end
    ): void {
        $settings = SystemSetting::whereIn('key', [
            'min_duration',
            'max_duration',
            'advance_days',
            'opening_time',
            'closing_time',
        ])->pluck('value', 'key');

        $minDuration = (float) ($settings['min_duration'] ?? 1);
        $maxDuration = (float) ($settings['max_duration'] ?? 4);
        $advanceDays = (int) ($settings['advance_days'] ?? 30);
        $openingTime = $settings['opening_time'] ?? '07:00';
        $closingTime = $settings['closing_time'] ?? '21:00';

        $today = now()->startOfDay();
        $reservationDate = \Illuminate\Support\Carbon::parse($date)->startOfDay();

        if ($reservationDate->lt($today)) {
            throw ValidationException::withMessages([
                'reservation_date' => 'Past reservation dates are not allowed.',
            ]);
        }

        if ($reservationDate->gt($today->copy()->addDays($advanceDays))) {
            throw ValidationException::withMessages([
                'reservation_date' => "Reservations can only be made up to {$advanceDays} days in advance.",
            ]);
        }

        if ($end <= $start) {
            throw ValidationException::withMessages([
                'end_time' => 'The end time must be later than the start time.',
            ]);
        }

        $duration = (strtotime($end) - strtotime($start)) / 3600;

        if ($duration < $minDuration) {
            throw ValidationException::withMessages([
                'end_time' => "The minimum reservation duration is {$minDuration} hour(s).",
            ]);
        }

        if ($duration > $maxDuration) {
            throw ValidationException::withMessages([
                'end_time' => "The maximum reservation duration is {$maxDuration} hour(s).",
            ]);
        }

        if ($start < $openingTime || $end > $closingTime) {
            throw ValidationException::withMessages([
                'start_time' => "Reservations must be within operating hours ({$openingTime} - {$closingTime}).",
            ]);
        }
    }

    public function cancel(Reservation $reservation, int $actorId): Reservation {
        return DB::transaction(function () use ($reservation, $actorId) {
            $reservation = Reservation::whereKey($reservation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($reservation->status !== 'Reserved') {
                throw ValidationException::withMessages([
                    'reservation' => 'This reservation is already cancelled.',
                ]);
            }

            $previousValues = $reservation->toArray();

            $reservation->update([
                'status' => 'Cancelled',
            ]);

            ReservationLog::create([
                'reservation_id' => $reservation->id,
                'user_id' => $actorId,
                'action' => 'Cancelled',
                'previous_values' => $previousValues,
                'new_values' => $reservation->fresh()->toArray(),
            ]);

            return $reservation->fresh();
        });
    }

    private function generateReservationNumber(): string {
        do {
            $number = 'ISK-' . now()->format('Ymd') . '-' .
                strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (Reservation::where('reservation_number', $number)->exists());

        return $number;
    }
}