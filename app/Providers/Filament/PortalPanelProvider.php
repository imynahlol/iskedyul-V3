<?php

namespace App\Providers\Filament;

use App\Filament\Portal\Pages\Dashboard;
use App\Filament\Portal\Pages\MyReservations;
use App\Filament\Portal\Pages\RoomAvailability;
use App\Filament\Portal\Pages\Auth\Login;
use App\Filament\Portal\Pages\Auth\Register;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PortalPanelProvider extends PanelProvider {
    public function panel(Panel $panel): Panel {
        return $panel
            ->id('portal')
            ->path('portal')
            ->login(Login::class)
            ->registration(Register::class)
            ->brandName('iSKEDYUL | Student Portal')
            ->font('Poppins')
            ->colors([
                'primary' => [
                    50 => '#fff5f5',
                    100 => '#ffe3e3',
                    200 => '#ffc9c9',
                    300 => '#ffa3a3',
                    400 => '#f87171',
                    500 => '#dc2626',
                    600 => '#b91c1c',
                    700 => '#991b1b',
                    800 => '#800000',
                    900 => '#660000',
                    950 => '#450000',
                ],
            ])
            ->discoverResources(
                in: app_path('Filament/Portal/Resources'),
                for: 'App\\Filament\\Portal\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Portal/Pages'),
                for: 'App\\Filament\\Portal\\Pages'
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Portal/Widgets'),
                for: 'App\\Filament\\Portal\\Widgets'
            )
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}