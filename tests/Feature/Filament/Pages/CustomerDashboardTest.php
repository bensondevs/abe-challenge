<?php

namespace Tests\Feature\Filament\Pages;

use App\Filament\Pages\CustomerDashboard;
use App\Models\Customer;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('can access customer dashboard when authenticated as customer', function () {
    $customer = Customer::factory()->create();

    actingAs($customer, 'customer');

    Filament::setCurrentPanel('customer');

    get(CustomerDashboard::getUrl())
        ->assertSuccessful();
});
