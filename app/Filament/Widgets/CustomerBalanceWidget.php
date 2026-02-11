<?php

namespace App\Filament\Widgets;

use App\Enums\Transaction\Type;
use App\Models\CreditTransaction;
use App\Models\Reward;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerBalanceWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $customer = auth('customer')->user();

        if (! $customer) {
            return [];
        }

        $balance = $customer->current_balance ?? 0;

        $transactions = CreditTransaction::query()
            ->where('customer_id', $customer->id)
            ->get();

        $totalEarned = $transactions
            ->where('amount', '>', 0)
            ->sum('amount');

        $totalSpent = abs($transactions
            ->where('amount', '<', 0)
            ->sum('amount'));

        $totalTransactions = $transactions->count();

        $bonusCount = $transactions
            ->where('type', Type::Bonus)
            ->count();

        $rewardCount = $transactions
            ->where('type', Type::Reward)
            ->count();

        $availableRewards = Reward::query()
            ->where('active', true)
            ->where('required_credits', '<=', $balance)
            ->count();

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

            Stat::make(
                'Available Rewards',
                number_format($availableRewards),
            )
                ->icon(Heroicon::OutlinedStar)
                ->color($availableRewards > 0 ? 'success' : 'gray')
                ->description('Rewards you can afford'),

            Stat::make(
                'Bonus Programs Applied',
                number_format($bonusCount),
            )
                ->icon(Heroicon::OutlinedGift)
                ->color('green')
                ->description('Bonuses received'),

            Stat::make(
                'Rewards Redeemed',
                number_format($rewardCount),
            )
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('amber')
                ->description('Rewards claimed'),
        ];
    }
}

