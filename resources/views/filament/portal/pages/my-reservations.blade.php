<x-filament-panels::page>
    <div class="space-y-6">

        @if ($editingReservationId)
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold">Edit Reservation</h2>
                        <p class="text-sm text-gray-500">
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

                <form wire:submit="saveChanges" class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium">Building</label>
                            <select
                                wire:model.live="building_id"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                            >
                                <option value="">Select Building</option>
                                @foreach ($buildings as $building)
                                    <option value="{{ $building->id }}">
                                        {{ $building->building_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('building_id')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">Room</label>
                            <select
                                wire:model="room_id"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                            >
                                <option value="">Select Room</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">
                                        {{ $room->room_code }}
                                        {{ $room->room_name ? ' - ' . $room->room_name : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">Reservation Date</label>
                            <input
                                type="date"
                                wire:model="reservation_date"
                                min="{{ now()->addDay()->toDateString() }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                            />
                            @error('reservation_date')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">Start Time</label>
                            <input
                                type="time"
                                wire:model="start_time"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                            />
                            @error('start_time')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">End Time</label>
                            <input
                                type="time"
                                wire:model="end_time"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                            />
                            @error('end_time')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">Purpose</label>
                            <input
                                type="text"
                                wire:model="purpose"
                                placeholder="Enter reservation purpose"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                            />
                            @error('purpose')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2 pt-3">
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

        <div>
            <h2 class="text-xl font-bold">Reservation History</h2>
            <p class="text-sm text-gray-500">
                View and manage your classroom reservations.
            </p>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-left dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3">Reservation No.</th>
                            <th class="px-4 py-3">Building</th>
                            <th class="px-4 py-3">Room</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Schedule</th>
                            <th class="px-4 py-3">Purpose</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($reservations as $reservation)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3 font-medium">
                                    {{ $reservation->reservation_number }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $reservation->building->building_name ?? '—' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $reservation->room->room_code ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3">
                                    {{ $reservation->reservation_date->format('M d, Y') }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3">
                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }}
                                    –
                                    {{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $reservation->purpose }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($reservation->status === 'Reserved')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Reserved
                                        </span>
                                    @elseif ($reservation->status === 'Cancelled')
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                            {{ $reservation->status }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-2">
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
                                            <span class="text-xs text-gray-400">No actions</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                                    You don't have any reservations yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-700">
                {{ $reservations->links() }}
            </div>
        </div>

    </div>
</x-filament-panels::page>