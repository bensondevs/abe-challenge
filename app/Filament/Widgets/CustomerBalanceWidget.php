<?php

namespace App\Filament\Widgets;

use App\Models\CreditTransaction;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerBalanceWidget extends StatsOverviewWidget
{
    public function getListeners(): array
    {
        return ['reward-redeemed' => '$refresh'];
    }

    protected function getStats(): array
    {
        $customer = auth('customer')->user();

        $balance = $customer->current_balance ?? 0;

        $transactions = CreditTransaction::query()
            ->where('customer_id', $customer->id)
            ->get();

        $totalEarned = $transactions
            ->where('amount', '>', 0)
            ->sum('amount');

        $totalSpent = abs(
            $transactions
                ->where('amount', '<', 0)
                ->sum('amount'),
        );

        return [
            Stat::make(
                'Current Credit Balance',
                number_format($balance).' credits',
            )
                ->icon(Heroicon::OutlinedBanknotes)
                ->color($balance >= 0 ? 'success' : 'danger')
                ->description('Your available credits'),

            Stat::make(
                'Total Credits Earned',
                number_format($totalEarned).' credits',
            )
                ->icon(Heroicon::OutlinedArrowDownCircle)
                ->color('success')
                ->description('All-time earnings'),

            Stat::make(
                'Total Credits Spent',
                number_format($totalSpent).' credits',
            )
                ->icon(Heroicon::OutlinedArrowUpCircle)
                ->color('warning')
                ->description('All-time spending'),
        ];
    }
}
