<?php

namespace App\Providers\Filament;

use App\Enums\PanelId;
use App\Filament\Outlet\Pages\Login;
use App\Filament\Outlet\Pages\OutletDashboard;
use App\Filament\Outlet\Pages\Pos;
use App\Filament\Outlet\Resources\Sale\Sales\Pages\SalesReport;
use App\Filament\Outlet\Resources\Sale\Sales\Widgets\OutletSaleStats;
use App\Models\Outlet\Outlet;
use App\Support\PanelConfiguration;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
            ->widgets([
                OutletSaleStats::class
            ])
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
            ])
            ->tenantRoutes(function () {
                // Route::get('/pos/bootstrap', [Pos::class, 'bootstrap'])->name('pos.bootstrap');
            });
    }
}
