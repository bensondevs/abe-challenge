<?php

use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('can access customer resource list page when authenticated', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(ListCustomers::class)
        ->assertSuccessful();
});
