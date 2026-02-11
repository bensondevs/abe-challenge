<?php

use App\Filament\Pages\AdministratorDashboard;
use App\Filament\Widgets\LoyaltyStatsOverviewWidget;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('renders loyalty stats overview widget on admin dashboard', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(AdministratorDashboard::class)
        ->assertSuccessful()
        ->assertSeeLivewire(LoyaltyStatsOverviewWidget::class);
});
