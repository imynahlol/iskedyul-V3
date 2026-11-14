<?php

namespace App\Filament\Portal\Pages;

use App\Models\Building;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\SystemSetting;
use App\Services\ReservationService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RoomAvailability extends Page {
    protected string $view = 'filament.portal.pages.room-availability';

    protected static ?string $title = 'Room Availability';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    public $building_id = '';
    public $room_id = '';
    public $reservation_date = '';
    public $start_time = '';
    public $end_time = '';
    public $purpose = '';

    public function updatedBuildingId(): void {
        $this->room_id = '';
    }

    public function getViewData(): array {
        $buildings = Building::where('status', 'Active')
            ->with(['rooms' => fn ($query) => $query->orderBy('room_code')])
            ->orderBy('building_name')
            ->get();

        $rooms = $this->building_id
            ? Room::where('building_id', $this->building_id)
                ->where('status', 'Active')
                ->orderBy('room_code')
                ->get()
            : collect();

        $reservedRoomIds = [];

        if ($this->reservation_date && $this->start_time && $this->end_time) {
            $reservedRoomIds = Reservation::whereDate('reservation_date', $this->reservation_date)
                ->where('status', 'Reserved')
                ->where('start_time', '<', $this->end_time)
                ->where('end_time', '>', $this->start_time)
                ->pluck('room_id')
                ->all();
        }

        $settings = SystemSetting::whereIn('key', [
            'min_duration',
            'max_duration',
            'advance_days',
            'opening_time',
            'closing_time',
        ])->pluck('value', 'key');

        return [
            'buildings' => $buildings,
            'rooms' => $rooms,
            'reservedRoomIds' => $reservedRoomIds,
            'minDuration' => (int) ($settings['min_duration'] ?? 1),
            'maxDuration' => (int) ($settings['max_duration'] ?? 4),
            'advanceDays' => (int) ($settings['advance_days'] ?? 30),
            'openingTime' => $settings['opening_time'] ?? '07:00',
            'closingTime' => $settings['closing_time'] ?? '21:00',
            'earliestDate' => now()->toDateString(),
            'latestDate' => now()->addDays((int) ($settings['advance_days'] ?? 30))->toDateString(),
        ];
    }

    public function submitReservation(): void {
        $settings = SystemSetting::whereIn('key', [
            'min_duration',
            'max_duration',
            'advance_days',
            'opening_time',
            'closing_time',
        ])->pluck('value', 'key');

        $advanceDays = (int) ($settings['advance_days'] ?? 30);

        $validator = Validator::make([
            'building_id' => $this->building_id,
            'room_id' => $this->room_id,
            'reservation_date' => $this->reservation_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'purpose' => $this->purpose,
        ], [
            'building_id' => ['required', 'exists:buildings,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'reservation_date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:' . now()->addDays($advanceDays)->toDateString(),
            ],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'purpose' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Notification::make()
                    ->title('Please check your reservation details')
                    ->body($error)
                    ->danger()
                    ->send();
            }

            return;
        }

        try {
            app(ReservationService::class)->create([
                'building_id' => $this->building_id,
                'room_id' => $this->room_id,
                'reservation_date' => $this->reservation_date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'purpose' => $this->purpose,
            ], (int) auth()->id());

            Notification::make()
                ->title('Reservation submitted!')
                ->body('Your classroom reservation has been successfully created.')
                ->success()
                ->send();

            $this->reset([
                'building_id',
                'room_id',
                'reservation_date',
                'start_time',
                'end_time',
                'purpose',
            ]);
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $errors) {
                foreach ($errors as $error) {
                    Notification::make()
                        ->title('Reservation unavailable')
                        ->body($error)
                        ->danger()
                        ->send();
                }
            }
        } catch (\Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Something went wrong')
                ->body('Your reservation could not be submitted. Please try again.')
                ->danger()
                ->send();
        }
    }
}