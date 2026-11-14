<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 28px;">

        {{-- Page Header --}}
        <div>
            <h2 style="font-size: 26px; font-weight: 700; margin: 0;">
                Find and Reserve a Classroom
            </h2>
            <p style="font-size: 14px; color: #6b7280; margin-top: 6px;">
                Select your preferred building, room, date, and schedule.
            </p>
        </div>

        {{-- Reservation Form --}}
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <h3 style="font-size: 19px; font-weight: 700; color: #800000; margin: 0 0 12px;">
                Reservation Details
            </h3>

            {{-- Reservation Rules --}}
            <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: 14px; margin-bottom: 22px; font-size: 13px; color: #9a3412;">
                <strong>Reservation Guidelines</strong>
                <ul style="margin: 8px 0 0; padding-left: 20px;">
                    <li>Minimum duration: {{ $minDuration }} hour(s)</li>
                    <li>Maximum duration: {{ $maxDuration }} hour(s)</li>
                    <li>Advance booking: Up to {{ $advanceDays }} days</li>
                    <li>Operating hours: {{ \Carbon\Carbon::parse($openingTime)->format('h:i A') }} – {{ \Carbon\Carbon::parse($closingTime)->format('h:i A') }}</li>
                </ul>
            </div>

            <form wire:submit="submitReservation">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px;">

                    {{-- Building --}}
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                            Building
                        </label>
                        <select wire:model.live="building_id"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                            <option value="">Select a building</option>
                            @foreach ($buildings as $building)
                                <option value="{{ $building->id }}">
                                    {{ $building->building_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Room --}}
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                            Room
                        </label>
                        <select wire:model="room_id"
                            @disabled(!$building_id)
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                            <option value="">Select a room</option>
                            @foreach ($rooms as $room)
                                @php
                                    $isReserved = in_array($room->id, $reservedRoomIds);
                                @endphp

                                <option value="{{ $room->id }}" @disabled($isReserved)>
                                    {{ $room->room_code }}
                                    {{ $room->room_name ? ' - ' . $room->room_name : '' }}
                                    {{ $isReserved ? ' - OCCUPIED' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Reservation Date --}}
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                            Reservation Date
                        </label>
                        <input type="date"
                            wire:model.live="reservation_date"
                            min="{{ $earliestDate }}"
                            max="{{ $latestDate }}"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                    </div>

                    {{-- Start Time --}}
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                            Start Time
                        </label>
                        <input type="time"
                            wire:model.live="start_time"
                            min="{{ $openingTime }}"
                            max="{{ $closingTime }}"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                    </div>

                    {{-- End Time --}}
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                            End Time
                        </label>
                        <input type="time"
                            wire:model.live="end_time"
                            min="{{ $openingTime }}"
                            max="{{ $closingTime }}"
                            style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827;">
                    </div>

                </div>

                {{-- Purpose --}}
                <div style="margin-top: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                        Purpose
                    </label>
                    <textarea wire:model="purpose"
                        rows="3"
                        placeholder="Explain the purpose of your classroom reservation..."
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #111827; resize: vertical;"></textarea>
                </div>

                {{-- Availability Notice --}}
                @if ($reservation_date && $start_time && $end_time && $end_time > $start_time)
                    <div style="margin-top: 20px; padding: 14px; border-radius: 8px; background: #eff6ff; color: #1e40af; font-size: 14px;">
                        <strong>Schedule availability:</strong>
                        Rooms already reserved during this time are marked below.
                    </div>
                @endif

                {{-- Submit Button --}}
                <div style="margin-top: 24px;">
                    <button type="submit"
                        wire:loading.attr="disabled"
                        style="background: #800000; color: white; padding: 13px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">
                        <span wire:loading.remove wire:target="submitReservation">
                            Submit Reservation
                        </span>
                        <span wire:loading wire:target="submitReservation">
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Campus Room Directory --}}
        <div>
            <div style="margin-bottom: 18px;">
                <h3 style="font-size: 20px; font-weight: 700; margin: 0;">
                    Campus Room Directory
                </h3>
                <p style="font-size: 13px; color: #6b7280; margin-top: 5px;">
                    Browse classrooms by building and check their current availability.
                </p>
            </div>

            @foreach ($buildings as $building)
                <section style="margin-bottom: 26px;">
                    <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 12px;">
                        {{ $building->building_name }}
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px;">
                        @foreach ($building->rooms as $room)
                            @php
                                $isReserved = in_array($room->id, $reservedRoomIds);
                                $isActive = $room->status === 'Active';
                            @endphp

                            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                    <span style="font-size: 15px; font-weight: 700;">
                                        {{ $room->room_code }}
                                    </span>

                                    @if (!$isActive)
                                        <span style="font-size: 12px; font-weight: 700; color: #dc2626;">Inactive</span>
                                    @elseif ($isReserved)
                                        <span style="font-size: 12px; font-weight: 700; color: #dc2626;">Occupied</span>
                                    @elseif ($reservation_date && $start_time && $end_time && $end_time > $start_time)
                                        <span style="font-size: 12px; font-weight: 700; color: #16a34a;">Available</span>
                                    @else
                                        <span style="font-size: 12px; font-weight: 600; color: #6b7280;">Select a schedule</span>
                                    @endif
                                </div>

                                <p style="font-size: 13px; color: #6b7280; margin: 8px 0 0;">
                                    {{ $room->room_name ?: 'Classroom' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

    </div>
</x-filament-panels::page>