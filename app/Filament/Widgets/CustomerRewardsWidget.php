<?php

namespace App\Filament\Widgets;

use App\Actions\RedeemReward;
use App\Models\Reward;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CustomerRewardsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => Reward::query()
                    ->where('active', true)
                    ->where(
                        'rewards.required_credits',
                        '<=',
                        auth('customer')->user()->current_balance,
                    ),
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('required_credits')
                    ->label('Required Credits')
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => number_format($state).' credits'),
            ])
            ->recordActions([
                Action::make('redeemReward')
                    ->icon(Heroicon::OutlinedGift)
                    ->hiddenLabel()
                    ->requiresConfirmation()
                    ->modalHeading('Redeeming reward')
                    ->modalDescription('Are you sure you want to redeem this reward? This action is irreversible.')
                    ->modalSubmitActionLabel('Redeem')
                    ->action(fn (RedeemReward $redeemReward, Reward $record) => $redeemReward(
                        customer: auth('customer')->user(),
                        reward: $record,
                    ))
                    ->after(fn () => $this->dispatch('reward-redeemed')),
            ])
            ->paginated(false)
            ->heading('Available Rewards')
            ->emptyStateHeading('No Rewards Available')
            ->emptyStateDescription(function (): string {
                $activeRewardsCount = Reward::query()
                    ->where('active', true)
                    ->count();

                return $activeRewardsCount === 0
                    ? 'No active rewards available.'
                    : 'There are '.$activeRewardsCount.' active rewards available, but you do not have enough credits to redeem them.';
            });
    }
}
