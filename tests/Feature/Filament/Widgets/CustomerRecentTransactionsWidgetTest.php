<?php

namespace Tests\Feature\Filament\Widgets;

use App\Filament\Pages\CustomerDashboard;
use App\Filament\Widgets\CustomerRecentTransactionsWidget;
use App\Models\Customer;
use Filament\Facades\Filament;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('renders customer recent transactions widget on customer dashboard', function () {
    $customer = Customer::factory()->create();

    actingAs($customer, 'customer');

    Filament::setCurrentPanel('customer');

    Livewire::test(CustomerDashboard::class)
        ->assertSuccessful()
        ->assertSeeLivewire(CustomerRecentTransactionsWidget::class);
});
