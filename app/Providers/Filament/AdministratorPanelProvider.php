<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AdministratorDashboard;
use App\Filament\Widgets\LoyaltyStatsOverviewWidget;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdministratorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('administrator')
            ->path('administrator')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->maxContentWidth(Width::Full)
            ->topNavigation()
            ->globalSearch(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                AdministratorDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                LoyaltyStatsOverviewWidget::class,
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
            ->unsavedChangesAlerts();
    }

    public function boot(): void
    {
        CreateRecord::alignFormActionsEnd();
        EditRecord::alignFormActionsEnd();

        Action::configureUsing(
            fn (Action $action) => $action
                ->when(
                    $action->isModalSlideOver(),
                    fn (Action $action) => $action
                        ->modalWidth(Width::ExtraLarge)
                        ->modalFooterActionsAlignment(Alignment::Center),
                ),
        );
        EditAction::configureUsing(
            fn (EditAction $action) => $action->hidden(
                fn (Model $record) => method_exists($record, 'getDeletedAtColumn')
                    && filled($record->{$record->getDeletedAtColumn()})
            ),
        );
        ForceDeleteAction::configureUsing(
            fn (ForceDeleteAction $action) => $action
                ->label('Delete permanently')
                ->modalHeading('PERMANENTLY DELETE RECORD')
                ->modalIcon(Heroicon::OutlinedFire)
                ->modalDescription('Are you sure you want to permanently delete this record? This action cannot be undone and the record will be removed from the database permanently.')
                ->modalSubmitActionLabel('DELETE'),
        );
    }
}
