<?php

namespace App\Support;

use Filament\Panel;
use Illuminate\View\View;
use App\Settings\GeneralSettings;
use Filament\Support\Enums\Width;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Route;
use Filament\FontProviders\GoogleFontProvider;
use Illuminate\Http\Request;

class PanelConfiguration
{
    protected GeneralSettings $generalSettings;

    public static function make(Panel $panel)
    {
        return (new static())->apply($panel);
    }

    public function apply(Panel $panel): Panel
    {
        $this->generalSettings = app(GeneralSettings::class);

        $panel
            ->font('Space Grotesk', provider: GoogleFontProvider::class)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName($this->generalSettings->site_name)
            ->brandLogo(fn() => $this->generalSettings->site_logo ? asset('storage/' . $this->generalSettings->site_logo) : null)
            ->spa(fn() => $this->generalSettings->spa_mode)
            // ->maxContentWidth($this->generalSettings->content_width ?? '7xl')
            // ->topNavigation(fn() => $this->generalSettings->navigation_type === 'topbar')
            ->simplePageMaxContentWidth(Width::Medium)
            ->profile()
            ->databaseTransactions()
            ->globalSearch(false)
            ->topbar(false)
            ->sidebarCollapsibleOnDesktop(fn() => request()->route()->getName() !== 'filament.outlet.pages.pos')
            ->sidebarFullyCollapsibleOnDesktop(fn() => request()->route()->getName() === 'filament.outlet.pages.pos')
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn(): View => view('partials.global-loading-indicator'),
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                hook: fn(): View => view('partials.light-switch')
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                hook: fn(): View => view('partials.about-me')
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                hook: fn(): View => view('partials.drag-modals')
            )
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                hook: fn(): View => view('partials.bprogress')
            )
            ->renderHook(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, fn(): View => view('partials.copyright-label'))
            ->colors([
                'primary' => [
                    50 => 'rgb(235, 255, 239)',
                    100 => 'rgb(204, 255, 212)',
                    200 => 'rgb(153, 255, 170)',
                    300 => 'rgb(102, 255, 128)',
                    400 => 'rgb(51, 239, 97)',
                    500 => 'rgb(26, 231, 85)',
                    600 => 'rgb(1, 223, 74)',
                    700 => 'rgb(0, 192, 64)',
                    800 => 'rgb(0, 153, 51)',
                    900 => 'rgb(0, 102, 34)',
                    950 => 'rgb(0, 51, 17)',
                ],
                'danger' => Color::Rose,
            ]);

        if ($this->generalSettings->background_type === 'pattern') {
            $panel->renderHook(PanelsRenderHook::CONTENT_BEFORE, fn(): View => view('partials.background-pattern'))
                ->renderHook(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, fn(): View => view('partials.background-pattern'));
        }

        return $panel;
    }
}
