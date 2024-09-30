<?php

namespace App\Providers\Filament;

use App\Filament\Resources\DishResource;
use App\Filament\Resources\TableResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    /**
     * Boot the panel provider.
     *
     * This method registers the custom stylesheet for the admin panel.
     *
     * @return void
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('custom-stylesheet', __DIR__ . '/../../../resources/css/custom.css'),
        ]);
    }

    /**
     * Configure the admin panel.
     *
     * This method sets up the navigation, resources, and middleware for the admin panel.
     *
     * @param Panel $panel The panel instance.
     * @return Panel The configured panel instance.
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->navigation(fn(NavigationBuilder $navigationBuilder) => $this->configureNavigation($navigationBuilder))
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(fn() => view('filament.logo'))
            ->favicon(asset('images/logo_icon.png'))
            ->breadcrumbs(true)
            ->databaseNotifications(fn() => $this->shouldEnableDatabaseNotifications())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware($this->getMiddleware())
            ->authMiddleware([Authenticate::class])
            ->plugins([FilamentShieldPlugin::make()]);
    }

    /**
     * Configure the navigation for the admin panel.
     *
     * @param NavigationBuilder $navigationBuilder The navigation builder instance.
     * @return NavigationBuilder The modified navigation builder.
     */
    protected function configureNavigation(NavigationBuilder $navigationBuilder): NavigationBuilder
    {
        $user = auth()->user();
        $role = $user?->roles->pluck('name')->first();

        $items = [
            ...Pages\Dashboard::getNavigationItems(),
        ];

        if ($role === 'super_admin') {
            $adminItems = $this->getAdminNavigationItems();

            // Add the group for admin items
            return $navigationBuilder
                ->items($items)
                ->groups([
                    NavigationGroup::make()
                        ->items($adminItems)
                        ->collapsible(false)
                        ->extraSidebarAttributes(['class' => 'separator-nav-group']),
                ]);
        }

        // For non-admin users, only return the dashboard
        return $navigationBuilder->items($items);
    }

    /**
     * Get the navigation items for admin users.
     *
     * @return NavigationItem[] The array of navigation items for admin users.
     */
    protected function getAdminNavigationItems(): array
    {
        return [
            ...TableResource::getNavigationItems(),
            NavigationItem::make()
                ->label('People Invited')
                ->url("#")
                ->icon('heroicon-o-user-group'),
            ...DishResource::getNavigationItems(),
            NavigationItem::make()
                ->label('Table setup')
                ->url("#")
                ->icon(false),
            NavigationItem::make()
                ->label('Settings')
                ->url("#")
                ->icon(false),
        ];
    }

    /**
     * Determine if database notifications should be enabled.
     *
     * @return bool True if enabled; otherwise, false.
     */
    protected function shouldEnableDatabaseNotifications(): bool
    {
        $user = auth()->user();
        $role = $user?->roles->pluck('name')->first();

        // Conditionally enable database notifications
        return $role === 'super_admin';
    }

    /**
     * Get the middleware for the admin panel.
     *
     * @return array The array of middleware classes.
     */
    protected function getMiddleware(): array
    {
        return [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];
    }
}
