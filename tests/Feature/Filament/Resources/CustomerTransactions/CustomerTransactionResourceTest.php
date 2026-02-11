<?php

namespace Tests\Feature\Filament\Resources\CustomerTransactions;

use App\Filament\Resources\CustomerTransactions\Pages\ListCustomerTransactions;
use App\Models\Customer;
use Filament\Facades\Filament;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('can access customer transaction resource list page when authenticated as customer', function () {
    $customer = Customer::factory()->create();

    actingAs($customer, 'customer');

    Filament::setCurrentPanel('customer');

    Livewire::test(ListCustomerTransactions::class)
        ->assertSuccessful();
});
