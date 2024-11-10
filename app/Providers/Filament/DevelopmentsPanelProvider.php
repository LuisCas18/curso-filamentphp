<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;

class DevelopmentsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('developments')
            ->path('developments')
            ->login()
            ->default()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Developments/Resources'), for: 'App\\Filament\\Developments\\Resources')
            ->discoverPages(in: app_path('Filament/Developments/Pages'), for: 'App\\Filament\\Developments\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Developments/Widgets'), for: 'App\\Filament\\Developments\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                SpotlightPlugin::make(),
            ])
            ->navigationItems([
                NavigationItem::make('SIBCLOUD ERP FILAMENT')
                    ->url('https://siccoms.com', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-presentation-chart-line')
                    ->sort(3),
            ])
            ->topNavigation()
            ->userMenuItems([
                MenuItem::make()
                    ->label('Administración')
                    ->url('/dashboard')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->visible(function (){
                        if(auth()->user()){
                            if(auth()->user()?->hasAnyRole([
                                'super_admin'
                            ])){
                                return true;
                            }else{
                                return false;
                            }
                        }else{
                            return false;
                        }
                        ;
                    }) ,
                // ...
            ]);
    }
}
