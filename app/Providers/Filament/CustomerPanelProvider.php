<?php

namespace App\Providers\Filament;

use App\Filament\Pages\CustomerDashboard;
use App\Filament\Resources\CustomerTransactions\CustomerTransactionResource;
use App\Filament\Widgets\CustomerBalanceWidget;
use App\Filament\Widgets\CustomerBonusProgramsWidget;
use App\Filament\Widgets\CustomerRecentTransactionsWidget;
use App\Filament\Widgets\CustomerRewardsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CustomerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('customer')
            ->path('customer')
            ->login()
            ->authGuard('customer')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->maxContentWidth(Width::Full)
            ->topNavigation()
            ->globalSearch(false)
            ->resources([
                CustomerTransactionResource::class,
            ])
            ->pages([
                CustomerDashboard::class,
            ])
            ->widgets([
                CustomerBalanceWidget::class,
                CustomerRecentTransactionsWidget::class,
                CustomerBonusProgramsWidget::class,
                CustomerRewardsWidget::class,
            ])
            ->resourceCreatePageRedirect('index')
            ->resourceEditPageRedirect('index')
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
            ->spa()
            ->unsavedChangesAlerts()
            ->databaseNotifications()
            ->databaseNotificationsPolling('15s');
    }
}
