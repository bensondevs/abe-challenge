<?php

namespace App\Filament\Widgets;

use App\Models\BonusProgram;
use App\Models\CreditTransaction;
use App\Models\Customer;
use App\Models\Reward;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LoyaltyStatsOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Customers',
                Customer::query()->withTrashed()->count(),
            )
                ->icon(Heroicon::OutlinedUsers)
                ->color('blue'),
            Stat::make(
                'Active Bonus Programs',
                BonusProgram::query()
                    ->where('active', true)
                    ->count(),
            )
                ->icon(Heroicon::OutlinedGift)
                ->color('green'),
            Stat::make(
                'Active Rewards',
                Reward::query()
                    ->where('active', true)
                    ->count(),
            )
                ->icon(Heroicon::OutlinedStar)
                ->color('amber'),
            Stat::make(
                'Total Credits Issued',
                CreditTransaction::query()->sum('amount') ?? 0,
            )
                ->icon(Heroicon::OutlinedBanknotes)
                ->color('cyan'),
        ];
    }
}
