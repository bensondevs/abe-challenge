<?php

namespace App\Filament\Widgets;

use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerBalanceWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $customer = auth('customer')->user();
        $balance = $customer?->current_balance ?? 0;

        return [
            Stat::make(
                'Current Credit Balance',
                number_format($balance).' credits',
            )
                ->icon(Heroicon::OutlinedBanknotes)
                ->color($balance >= 0 ? 'success' : 'danger')
                ->description('Your available credits'),
        ];
    }
}

