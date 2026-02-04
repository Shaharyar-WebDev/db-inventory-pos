<?php

namespace App\Providers\Filament;

use Filament\Panel;
use App\Enums\PanelId;
use Filament\PanelProvider;
use App\Models\Outlet\Outlet;
use Filament\Support\Colors\Color;
use App\Support\PanelConfiguration;
use App\Filament\Outlet\Pages\Login;
use App\Filament\Outlet\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class OutletPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return PanelConfiguration::make($panel)
            ->id(PanelId::OUTLET->id())
            ->path(PanelId::OUTLET->path())
            ->login(Login::class)
            ->default()
            ->searchableTenantMenu()
            ->tenant(Outlet::class, 'name', 'outlet')
            ->discoverResources(in: app_path('Filament/Outlet/Resources'), for: 'App\Filament\Outlet\Resources')
            ->discoverPages(in: app_path('Filament/Outlet/Pages'), for: 'App\Filament\Outlet\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Outlet/Widgets'), for: 'App\Filament\Outlet\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([])
            ->tenantMiddleware([], isPersistent: true)
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
