<?php

namespace App\Filament\Portal\Pages;

use App\Models\Building;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\ReservationService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class MyReservations extends Page {
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'My Reservations';

    protected static ?string $title = 'My Reservations';

    protected string $view = 'filament.portal.pages.my-reservations';

    public ?int $editingReservationId = null;

    public ?int $building_id = null;

    public ?int $room_id = null;

    public ?string $reservation_date = null;

    public ?string $start_time = null;

    public ?string $end_time = null;

    public ?string $purpose = null;

    public function getViewData(): array {
        return [
            'reservations' => Reservation::with(['building', 'room'])
                ->where('user_id', auth()->id())
                ->latest('reservation_date')
                ->latest('start_time')
                ->paginate(10),

            'buildings' => Building::where('status', 'Active')
                ->orderBy('building_name')
                ->get(),

            'rooms' => Room::where('building_id', $this->building_id)
                ->where('status', 'Active')
                ->orderBy('room_code')
                ->get(),
        ];
    }

    public function updatedBuildingId(): void {
        $this->room_id = null;
    }

    public function editReservation(int $reservationId): void {
        $reservation = Reservation::where('user_id', auth()->id())
            ->where('status', 'Reserved')
            ->findOrFail($reservationId);

        if ($reservation->reservation_date->toDateString() <= now()->toDateString()) {
            Notification::make()
                ->title('Editing unavailable')
                ->body('Reservations can only be edited before their scheduled date.')
                ->warning()
                ->send();

            return;
        }

        $this->editingReservationId = $reservation->id;
        $this->building_id = $reservation->building_id;
        $this->room_id = $reservation->room_id;
        $this->reservation_date = $reservation->reservation_date->format('Y-m-d');
        $this->start_time = substr($reservation->start_time, 0, 5);
        $this->end_time = substr($reservation->end_time, 0, 5);
        $this->purpose = $reservation->purpose;
    }

    public function saveChanges(): void {
        try {
            $this->validate([
                'building_id' => ['required', 'exists:buildings,id'],
                'room_id' => [
                    'required',
                    Rule::exists('rooms', 'id')
                        ->where('building_id', $this->building_id)
                        ->where('status', 'Active'),
                ],
                'reservation_date' => ['required', 'date', 'after:today'],
                'start_time' => ['required', 'date_format:H:i'],
                'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
                'purpose' => ['required', 'string', 'max:1000'],
            ]);

            $reservation = Reservation::where('user_id', auth()->id())
                ->findOrFail($this->editingReservationId);

            app(ReservationService::class)->update(
                $reservation,
                [
                    'building_id' => $this->building_id,
                    'room_id' => $this->room_id,
                    'reservation_date' => $this->reservation_date,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'purpose' => $this->purpose,
                ],
                (int) auth()->id()
            );

            $this->resetEditing();

            Notification::make()
                ->title('Reservation updated')
                ->body('Your reservation details have been updated successfully.')
                ->success()
                ->send();

        } catch (ValidationException $e) {
            Notification::make()
                ->title('Unable to update reservation')
                ->body(collect($e->errors())->flatten()->first())
                ->warning()
                ->send();

        } catch (ModelNotFoundException $e) {
            Notification::make()
                ->title('Reservation not found')
                ->body('You can only edit your own reservations.')
                ->danger()
                ->send();

        } catch (Throwable $e) {
            Notification::make()
                ->title('Update failed')
                ->body('The reservation could not be updated. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function resetEditing(): void {
        $this->editingReservationId = null;
        $this->building_id = null;
        $this->room_id = null;
        $this->reservation_date = null;
        $this->start_time = null;
        $this->end_time = null;
        $this->purpose = null;
    }

    public function cancelReservation(int $reservationId): void {
        try {
            $reservation = Reservation::where('user_id', auth()->id())
                ->findOrFail($reservationId);

            if ($reservation->status !== 'Reserved') {
                Notification::make()
                    ->title('Reservation cannot be cancelled')
                    ->body('Only active reservations can be cancelled.')
                    ->warning()
                    ->send();

                return;
            }

            app(ReservationService::class)->cancel(
                $reservation,
                (int) auth()->id()
            );

            Notification::make()
                ->title('Reservation cancelled')
                ->success()
                ->send();

        } catch (ModelNotFoundException $e) {
            Notification::make()
                ->title('Reservation not found')
                ->danger()
                ->send();

        } catch (Throwable $e) {
            Notification::make()
                ->title('Cancellation failed')
                ->body('Please try again.')
                ->danger()
                ->send();
        }
    }
}