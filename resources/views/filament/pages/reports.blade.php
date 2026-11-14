<x-filament-panels::page>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 12mm;
            }

            body {
                background: white !important;
            }

            .report-actions {
                display: none !important;
            }

            .report-card {
                break-inside: avoid;
                box-shadow: none !important;
            }
        }
    </style>

    <div class="space-y-6">

        {{-- Report Header --}}
        <div class="report-card rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Reservation Reports and Analytics
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Monitor classroom utilization and reservation activity at PUP Lopez Campus.
                    </p>
                </div>

                <div class="report-actions flex flex-wrap gap-2">
                    <button
                        type="button"
                        wire:click="exportExcel"
                        style="background: #15803d; color: white; padding: 11px 18px; border-radius: 8px; font-weight: 600; cursor: pointer;"
                    >
                        Export Excel
                    </button>

                    <button
                        type="button"
                        onclick="window.print()"
                        style="background: #2563eb; color: white; padding: 11px 18px; border-radius: 8px; font-weight: 600; cursor: pointer;"
                    >
                        Print / Save PDF
                    </button>
                </div>
            </div>
        </div>

        {{-- Reservation Status --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            <div class="report-card rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Reservation Status
                </h3>

                @foreach ([
                    ['label' => 'Reserved', 'count' => $reserved, 'percent' => $reservedPercent, 'color' => 'bg-green-500', 'text' => 'text-green-600'],
                    ['label' => 'Cancelled', 'count' => $cancelled, 'percent' => $cancelledPercent, 'color' => 'bg-red-500', 'text' => 'text-red-600'],
                    ['label' => 'Completed', 'count' => $completed, 'percent' => $completedPercent, 'color' => 'bg-blue-500', 'text' => 'text-blue-600'],
                ] as $status)
                    <div class="mt-5">
                        <div class="mb-2 flex justify-between text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-300">
                                {{ $status['label'] }}
                            </span>
                            <span class="font-semibold {{ $status['text'] }}">
                                {{ $status['count'] }}
                            </span>
                        </div>

                        <div class="h-3 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                            <div
                                class="h-3 rounded-full {{ $status['color'] }}"
                                style="width: {{ $status['percent'] }}%"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Most Used Buildings --}}
            <div class="report-card rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Most Used Buildings
                </h3>

                <div class="mt-4 space-y-4">
                    @forelse ($buildings as $item)
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->building?->building_name ?? 'Unknown Building' }}
                            </span>

                            <span class="rounded-lg bg-red-50 px-3 py-1 text-sm font-semibold text-red-800 dark:bg-red-950 dark:text-red-300">
                                {{ $item->total }} bookings
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">
                            No reservation records yet.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Most Reserved Rooms --}}
        <div class="report-card rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h3 class="font-semibold text-gray-900 dark:text-white">
                Most Reserved Rooms
            </h3>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 text-gray-500 dark:border-gray-700">
                        <tr>
                            <th class="py-3 pr-4">Room</th>
                            <th class="py-3 pr-4">Building</th>
                            <th class="py-3 text-right">Total Bookings</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($rooms as $item)
                            <tr>
                                <td class="py-3 pr-4 font-medium text-gray-900 dark:text-white">
                                    {{ $item->room?->room_code ?? 'Unknown Room' }}
                                </td>

                                <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">
                                    {{ $item->building?->building_name ?? 'Unknown Building' }}
                                </td>

                                <td class="py-3 text-right font-semibold text-gray-900 dark:text-white">
                                    {{ $item->total }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-gray-500">
                                    No reservation records yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- User Distribution --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            <div class="report-card rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Student Reservations
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $studentReservations }}
                </p>
            </div>

            <div class="report-card rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Faculty Reservations
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $facultyReservations }}
                </p>
            </div>

        </div>

        <p class="text-xs text-gray-400">
            Reports are based on reservation records currently stored in the iSKEDYUL database.
        </p>

    </div>
</x-filament-panels::page>