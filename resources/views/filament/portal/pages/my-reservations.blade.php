<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 26px;">

        {{-- Page Header --}}
        <div>
            <h2 style="font-size: 26px; font-weight: 700; margin: 0;">
                Reservation History
            </h2>
            <p style="font-size: 14px; color: #6b7280; margin-top: 6px;">
                View and manage your classroom reservations.
            </p>
        </div>

        {{-- Edit Reservation Form --}}
        @if ($editingReservationId)
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 22px;">
                    <div>
                        <h3 style="font-size: 19px; font-weight: 700; color: #800000; margin: 0;">
                            Edit Reservation
                        </h3>
                        <p style="font-size: 13px; color: #6b7280; margin-top: 5px;">
                            Update your reservation details.
                        </p>
                    </div>

                    <x-filament::button
                        color="gray"
                        outlined
                        wire:click="resetEditing"
                    >
                        Close
                    </x-filament::button>
                </div>

                {{-- Reservation Guidelines --}}
                <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 10px; padding: 15px 18px; margin-bottom: 22px;">
                    <div style="font-size: 14px; font-weight: 700; color: #9a3412; margin-bottom: 9px;">
                        Reservation Guidelines
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; font-size: 13px; color: #7c2d12;">
                        <div>
                            <strong>Minimum Duration:</strong>
                            {{ $minDuration }} hour(s)
                        </div>
                        <div>
                            <strong>Maximum Duration:</strong>
                            {{ $maxDuration }} hour(s)
                        </div>
                        <div>
                            <strong>Advance Booking:</strong>
                            Up to {{ $advanceDays }} days
                        </div>
                        <div>
                            <strong>Operating Hours:</strong>
                            {{ \Carbon\Carbon::parse($openingTime)->format('h:i A') }}
                            – 
                            {{ \Carbon\Carbon::parse($closingTime)->format('h:i A') }}
                        </div>
                    </div>

                    <p style="font-size: 12px; color: #9a3412; margin-top: 10px;">
                        Your updated reservation must follow these guidelines and must not conflict with another reservation.
                    </p>
                </div>

                <form wire:submit="saveChanges">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 18px;">

                        {{-- Building --}}
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Building</label>
                            <select wire:model.live="building_id"
                                style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                                <option value="">Select Building</option>
                                @foreach ($buildings as $building)
                                    <option value="{{ $building->id }}">{{ $building->building_name }}</option>
                                @endforeach
                            </select>
                            @error('building_id')
                                <p style="font-size: 12px; color: #dc2626; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Room --}}
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Room</label>
                            <select wire:model="room_id"
                                style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                                <option value="">Select Room</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">
                                        {{ $room->room_code }}
                                        {{ $room->room_name ? ' - ' . $room->room_name : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <p style="font-size: 12px; color: #dc2626; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date --}}
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Reservation Date</label>
                            <input type="date" wire:model="reservation_date"
                                min="{{ $earliestDate }}"
                                max="{{ $latestDate }}"
                                style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                            @error('reservation_date')
                                <p style="font-size: 12px; color: #dc2626; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Start Time --}}
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Start Time</label>
                            <input type="time" wire:model="start_time"
                                min="{{ $openingTime }}"
                                max="{{ $closingTime }}"
                                style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                            @error('start_time')
                                <p style="font-size: 12px; color: #dc2626; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- End Time --}}
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">End Time</label>
                            <input type="time" wire:model="end_time"
                                min="{{ $openingTime }}"
                                max="{{ $closingTime }}"
                                style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                            @error('end_time')
                                <p style="font-size: 12px; color: #dc2626; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Purpose --}}
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Purpose</label>
                            <input type="text" wire:model="purpose"
                                placeholder="Enter reservation purpose"
                                style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                            @error('purpose')
                                <p style="font-size: 12px; color: #dc2626; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div style="display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 10px; margin-top: 24px;">
                        <x-filament::button
                            color="gray"
                            outlined
                            wire:click="resetEditing"
                        >
                            Discard
                        </x-filament::button>

                        <x-filament::button type="submit">
                            Save Changes
                        </x-filament::button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Reservation Table --}}
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

            <div style="padding: 20px 22px; border-bottom: 1px solid #e5e7eb;">
                <h3 style="font-size: 18px; font-weight: 700; margin: 0;">
                    My Reservations
                </h3>
                <p style="font-size: 13px; color: #6b7280; margin-top: 5px;">
                    Your submitted classroom reservation records.
                </p>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; min-width: 1050px; border-collapse: collapse; text-align: left; font-size: 13px;">

                    <thead style="background: #f9fafb; color: #4b5563;">
                        <tr>
                            <th style="padding: 15px;">Reservation No.</th>
                            <th style="padding: 15px;">Building</th>
                            <th style="padding: 15px;">Room</th>
                            <th style="padding: 15px;">Date</th>
                            <th style="padding: 15px;">Schedule</th>
                            <th style="padding: 15px;">Purpose</th>
                            <th style="padding: 15px;">Status</th>
                            <th style="padding: 15px; text-align: center;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($reservations as $reservation)
                            <tr style="border-top: 1px solid #e5e7eb;">

                                <td style="padding: 15px; font-weight: 600; white-space: nowrap;">
                                    {{ $reservation->reservation_number }}
                                </td>

                                <td style="padding: 15px;">
                                    {{ $reservation->building->building_name ?? '—' }}
                                </td>

                                <td style="padding: 15px;">
                                    {{ $reservation->room->room_code ?? '—' }}
                                </td>

                                <td style="padding: 15px; white-space: nowrap;">
                                    {{ $reservation->reservation_date->format('M d, Y') }}
                                </td>

                                <td style="padding: 15px; white-space: nowrap;">
                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }}
                                    –
                                    {{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}
                                </td>

                                <td style="padding: 15px; max-width: 200px;">
                                    {{ $reservation->purpose }}
                                </td>

                                {{-- Status --}}
                                <td style="padding: 15px; white-space: nowrap;">
                                    @if ($reservation->status === 'Reserved')
                                        <span style="display: inline-block; background: #dcfce7; color: #166534; padding: 6px 11px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                                            Reserved
                                        </span>
                                    @elseif ($reservation->status === 'Cancelled')
                                        <span style="display: inline-block; background: #fee2e2; color: #991b1b; padding: 6px 11px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                                            Cancelled
                                        </span>
                                    @else
                                        <span style="display: inline-block; background: #f3f4f6; color: #374151; padding: 6px 11px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                                            {{ $reservation->status }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td style="padding: 15px;">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 7px;">
                                        @if ($reservation->status === 'Reserved')
                                            @if ($reservation->reservation_date->toDateString() > now()->toDateString())
                                                <x-filament::button
                                                    color="primary"
                                                    size="sm"
                                                    outlined
                                                    wire:click="editReservation({{ $reservation->id }})"
                                                >
                                                    Edit
                                                </x-filament::button>
                                            @endif

                                            <x-filament::button
                                                color="danger"
                                                size="sm"
                                                outlined
                                                wire:click="cancelReservation({{ $reservation->id }})"
                                                wire:confirm="Are you sure you want to cancel this reservation?"
                                            >
                                                Cancel
                                            </x-filament::button>
                                        @else
                                            <span style="font-size: 12px; color: #9ca3af;">No actions</span>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 42px 20px; text-align: center; color: #6b7280;">
                                    <div style="font-size: 16px; font-weight: 600; margin-bottom: 6px;">
                                        No reservations yet
                                    </div>
                                    <p style="font-size: 13px;">
                                        Your classroom reservations will appear here once submitted.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div style="padding: 14px 20px; border-top: 1px solid #e5e7eb;">
                {{ $reservations->links() }}
            </div>

        </div>
    </div>
</x-filament-panels::page>