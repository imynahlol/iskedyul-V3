<x-filament-panels::page>
    <div>
        <div style="margin-bottom: 24px;">
            <h2 style="font-size: 24px; font-weight: 700;">
                Welcome back, {{ auth()->user()->name }}!
            </h2>
            <p style="font-size: 14px; color: #6b7280;">
                Here's your iSKEDYUL reservation overview.
            </p>
        </div>

        {{-- Reservation Statistics --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            @foreach ([
                ['label' => 'Total Reservations', 'value' => $totalReservations],
                ['label' => 'Upcoming Reservations', 'value' => $upcomingReservations],
                ['label' => 'Active Reservations', 'value' => $activeReservations],
                ['label' => 'Cancelled Reservations', 'value' => $cancelledReservations],
            ] as $stat)
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                    <p style="font-size: 14px; color: #6b7280; margin: 0;">
                        {{ $stat['label'] }}
                    </p>
                    <p style="font-size: 32px; font-weight: 700; color: #800000; margin: 12px 0 0;">
                        {{ $stat['value'] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Quick Actions --}}
        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;">
            <a href="{{ \App\Filament\Portal\Pages\RoomAvailability::getUrl() }}"
                style="background: #800000; color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none;">
                Browse Available Rooms
            </a>

            <a href="{{ \App\Filament\Portal\Pages\MyReservations::getUrl() }}"
                style="border: 1px solid #d1d5db; padding: 12px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none;">
                View My Reservations
            </a>
        </div>

        {{-- Recent Reservations --}}
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px;">
            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">
                Recent Reservations
            </h3>

            <div style="overflow-x: auto;">
                <table style="width: 100%; text-align: left; font-size: 14px; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid #e5e7eb; color: #6b7280;">
                            <th style="padding: 12px;">Reservation No.</th>
                            <th style="padding: 12px;">Room</th>
                            <th style="padding: 12px;">Date</th>
                            <th style="padding: 12px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentReservations as $reservation)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px;">{{ $reservation->reservation_number }}</td>
                                <td style="padding: 12px;">{{ $reservation->room->room_code ?? '-' }}</td>
                                <td style="padding: 12px;">{{ $reservation->reservation_date->format('M d, Y') }}</td>
                                <td style="padding: 12px;">{{ $reservation->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 24px; text-align: center; color: #6b7280;">
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