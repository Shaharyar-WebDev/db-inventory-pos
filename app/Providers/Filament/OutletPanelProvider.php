<?php

namespace App\Providers\Filament;

use Filament\Panel;
use App\Enums\PanelId;
use Filament\PanelProvider;
use Filament\Actions\Action;
use App\Models\Outlet\Outlet;
use App\Support\PanelConfiguration;
use App\Filament\Outlet\Pages\Login;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Outlet\Pages\OutletDashboard;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Facades\Filament;
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
                OutletDashboard::class,
            ])
            ->userMenuItems([
                Action::make('go_to_admin_panel')
                    ->url(fn(): string => url(Filament::getPanel(PanelId::ADMIN->id())->getPath()))
                    ->visible(fn(Panel $filament) => $filament->auth()->user()->hasRole('super_admin'))
                    ->icon('heroicon-o-arrow-right'),
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
            ->plugins([
                // FilamentShieldPlugin::make(),
            ])
            ->plugins([])
            ->tenantMiddleware([], isPersistent: true)
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
