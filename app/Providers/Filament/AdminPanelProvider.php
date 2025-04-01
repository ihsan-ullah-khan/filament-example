<?php

namespace App\Providers\Filament;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/')
            ->login()
            ->colors([
                'primary' => Color::rgb('rgb(67,56,202)'),
                'gray' => Color::Gray
            ])
            ->userMenuItems([
                UserMenuItem::make()
                    ->label('Edit Profile')
                    ->icon('heroicon-o-pencil')
                    ->url('/profile')
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(app_path('Filament/Clusters'), 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
//            ->userMenuItems([
//                MenuItem::make()
//                ->label('profile')
//                ->url(fn() : string => Settings::getUrl())
//                ->icon('heroicon-o-user'),
//            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->navigationGroups([
                'Country'
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
//            ->plugins([
//                FilamentEditProfilePlugin::make()
//                    ->customProfileComponents([
//                        CustomProfileComponent::class,
//                    ])
//            ])
//            ->userMenuItems([
//                'profile' => MenuItem::make()
//                    ->label(fn() => auth()->user()->name)
//                    ->url(fn (): string => EditProfilePage::getUrl())
//                    ->icon('heroicon-m-user-circle'),
//                    //If you are using tenancy need to check with the visible method where ->company() is the relation between the user and tenancy model as you called
//
//            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
