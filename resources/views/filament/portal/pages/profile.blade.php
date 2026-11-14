<x-filament-panels::page>
    <div style="max-width: 850px; margin: auto; width: 100%;">
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 14px; padding: 28px; color: #1f2937;">

            <div style="margin-bottom: 25px;">
                <h2 style="font-size: 20px; font-weight: 700;">Personal Information</h2>
                <p style="font-size: 13px; color: #6b7280; margin-top: 5px;">
                    View and update your account information.
                </p>
            </div>

            <div style="background: #f8fafc; padding: 18px; border-radius: 10px; margin-bottom: 22px;">
                <p style="font-size: 12px; color: #64748b;">OFFICIAL USER ID</p>
                <p style="font-size: 18px; font-weight: 700; margin-top: 5px;">
                    {{ $userId }}
                </p>

                <p style="font-size: 12px; color: #64748b; margin-top: 8px;">
                    Account Type: {{ $userType }}
                </p>

                @if ($userType === 'Student')
                    <div style="margin-top: 15px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                        <div>
                            <p style="font-size: 11px; color: #64748b;">PROGRAM</p>
                            <p style="font-size: 13px; font-weight: 600;">{{ $program ?: 'Not set' }}</p>
                        </div>

                        <div>
                            <p style="font-size: 11px; color: #64748b;">YEAR LEVEL</p>
                            <p style="font-size: 13px; font-weight: 600;">{{ $yearLevel ?: 'Not set' }}</p>
                        </div>

                        <div>
                            <p style="font-size: 11px; color: #64748b;">SECTION</p>
                            <p style="font-size: 13px; font-weight: 600;">{{ $section ?: 'Not set' }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <form wire:submit="saveProfile">
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 7px;">
                        Full Name
                    </label>

                    <input
                        type="text"
                        wire:model="name"
                        required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; color: #111827;"
                    >

                    @error('name')
                        <p style="color: #dc2626; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 22px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 7px;">
                        Email Address
                    </label>

                    <input
                        type="email"
                        wire:model="email"
                        placeholder="Enter your email address"
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; color: #111827;"
                    >

                    @error('email')
                        <p style="color: #dc2626; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    style="background: #2563eb; color: white; padding: 12px 22px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;"
                >
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</x-filament-panels::page>