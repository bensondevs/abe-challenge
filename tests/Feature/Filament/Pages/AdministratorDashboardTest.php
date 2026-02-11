<?php

namespace Tests\Feature\Filament\Pages;

use App\Filament\Pages\AdministratorDashboard;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('can access administrator dashboard when authenticated', function () {
    $user = User::factory()->create();

    actingAs($user);

    get(AdministratorDashboard::getUrl())
        ->assertSuccessful();
});
