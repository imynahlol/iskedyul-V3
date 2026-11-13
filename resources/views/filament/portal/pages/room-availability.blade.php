<x-filament-panels::page>
    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-bold">Find and Reserve a Classroom</h2>
            <p class="text-sm text-gray-500">
                Select your preferred building, room, date, and schedule.
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h3 class="mb-5 text-lg font-bold text-[#800000]">Reservation Details</h3>

            <form wire:submit="submitReservation" class="space-y-5">

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Building</label>
                        <select wire:model.live="building_id"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                            <option value="">Select a building</option>
                            @foreach ($buildings as $building)
                                <option value="{{ $building->id }}">
                                    {{ $building->building_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Room</label>
                        <select wire:model="room_id"
                            @disabled(!$building_id)
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                            <option value="">Select a room</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">
                                    {{ $room->room_code }}
                                    {{ $room->room_name ? ' - ' . $room->room_name : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Reservation Date</label>
                        <input type="date"
                            wire:model.live="reservation_date"
                            min="{{ now()->toDateString() }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Start Time</label>
                        <input type="time"
                            wire:model.live="start_time"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">End Time</label>
                        <input type="time"
                            wire:model.live="end_time"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                    </div>

                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Purpose</label>
                    <textarea wire:model="purpose"
                        rows="3"
                        placeholder="Explain the purpose of your classroom reservation..."
                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"></textarea>
                </div>

                @if ($reservation_date && $start_time && $end_time && $end_time > $start_time)
                    <div class="rounded-lg bg-blue-50 p-4 text-sm text-blue-800 dark:bg-blue-950 dark:text-blue-200">
                        <strong>Schedule availability:</strong>
                        Rooms already reserved during this time are marked below.
                    </div>
                @endif

                <button type="submit"
                    wire:loading.attr="disabled"
                    class="rounded-lg bg-[#800000] px-6 py-3 text-sm font-semibold text-white hover:bg-[#660000] disabled:opacity-50">
                    <span wire:loading.remove wire:target="submitReservation">
                        Submit Reservation
                    </span>
                    <span wire:loading wire:target="submitReservation">
                        Processing...
                    </span>
                </button>

            </form>
        </div>

        <div>
            <h3 class="mb-4 text-lg font-bold">Campus Room Directory</h3>

            @foreach ($buildings as $building)
                <section class="mb-6 space-y-3">
                    <h4 class="font-bold">{{ $building->building_name }}</h4>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($building->rooms as $room)
                            @php
                                $isReserved = in_array($room->id, $reservedRoomIds);
                                $isActive = $room->status === 'Active';
                            @endphp

                            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-bold">{{ $room->room_code }}</span>

                                    @if (!$isActive)
                                        <span class="text-xs font-semibold text-red-600">Inactive</span>
                                    @elseif ($isReserved)
                                        <span class="text-xs font-semibold text-red-600">Occupied</span>
                                    @elseif ($reservation_date && $start_time && $end_time)
                                        <span class="text-xs font-semibold text-green-600">Available</span>
                                    @else
                                        <span class="text-xs font-semibold text-gray-500">Select a schedule</span>
                                    @endif
                                </div>

                                <p class="mt-2 text-sm text-gray-500">
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