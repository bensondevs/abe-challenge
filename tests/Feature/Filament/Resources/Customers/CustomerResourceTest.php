<?php

namespace Tests\Feature\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('can access customer resource list page when authenticated', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(ListCustomers::class)
        ->assertSuccessful();
});

it('can access customer create page', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->assertSuccessful();
});

it('can access customer view page', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(ViewCustomer::class, ['record' => $customer->id])
        ->assertSuccessful();
});

it('can access customer edit page', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->assertSuccessful();
});
