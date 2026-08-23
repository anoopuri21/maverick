<?php

namespace App\Providers\Filament;

use App\Filament\Support\LenientFormValidation;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        LenientFormValidation::register();
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->sidebarCollapsibleOnDesktop(true)
            ->navigationGroups([
                'Homepage',
                'About Section',
                'Programs',
                'Global Pathways',
                'Insights',
                'Global Content',
                'Our Story Page',
                'Site Settings',
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::PAGE_END,
                fn (): string => Blade::render(<<<'HTML'
                    <script>
                        // Accordion behaviour for the Programme "Detail Sections" tab.
                        // Expanding a section closes all its siblings, so only a single
                        // section is open at a time. Runs in the capture phase (before
                        // Filament's own toggle) and only acts when the clicked section
                        // is currently collapsed (i.e. about to open).
                        document.addEventListener('click', (e) => {
                            const header = e.target.closest('header');
                            if (! header) return;
                            const section = header.parentElement;
                            if (! section || ! section.matches('[data-pd-accordion]')) return;
                            if (! section.classList.contains('fi-collapsed')) return; // collapsing, not opening
                            const group = section.getAttribute('data-pd-accordion-group');
                            if (! group) return;
                            document.querySelectorAll('[data-pd-accordion-group="' + group + '"]').forEach((sib) => {
                                if (sib !== section && ! sib.classList.contains('fi-collapsed')) {
                                    window.dispatchEvent(new CustomEvent('collapse-section', { detail: { id: sib.id } }));
                                }
                            });
                        }, true);
                    </script>
                    HTML,
                ),
            );
    }
}