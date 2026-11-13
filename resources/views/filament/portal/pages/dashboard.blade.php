<x-filament-panels::page>
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-sm text-gray-500">Here's your iSKEDYUL reservation overview.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Total Reservations', 'value' => $totalReservations],
                ['label' => 'Upcoming Reservations', 'value' => $upcomingReservations],
                ['label' => 'Active Reservations', 'value' => $activeReservations],
                ['label' => 'Cancelled Reservations', 'value' => $cancelledReservations],
            ] as $stat)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                    <p class="mt-2 text-3xl font-bold text-[#800000]">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ \App\Filament\Portal\Pages\RoomAvailability::getUrl() }}"
                class="rounded-lg bg-[#800000] px-5 py-3 text-sm font-semibold text-white">
                Browse Available Rooms
            </a>

            <a href="{{ \App\Filament\Portal\Pages\MyReservations::getUrl() }}"
                class="rounded-lg border border-gray-300 px-5 py-3 text-sm font-semibold">
                View My Reservations
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-bold">Recent Reservations</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b text-gray-500">
                            <th class="p-3">Reservation No.</th>
                            <th class="p-3">Room</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentReservations as $reservation)
                            <tr class="border-b dark:border-gray-700">
                                <td class="p-3 font-medium">{{ $reservation->reservation_number }}</td>
                                <td class="p-3">{{ $reservation->room->room_code ?? '-' }}</td>
                                <td class="p-3">{{ $reservation->reservation_date->format('M d, Y') }}</td>
                                <td class="p-3">{{ $reservation->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-5 text-center text-gray-500">
                                    You don't have any reservations yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>