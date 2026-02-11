<?php

namespace App\Filament\Resources\Customers\Actions;

use App\Actions\RedeemReward;
use App\Models\Customer;
use App\Models\Reward;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class RedeemRewardAction
{
    public static function make(): Action
    {
        return Action::make('redeemReward')
            ->label('Redeem Reward')
            ->icon(Heroicon::OutlinedStar)
            ->color('warning')
            ->schema([
                Select::make('reward_id')
                    ->label('Reward')
                    ->options(
                        fn () => Reward::query()
                            ->where('active', true)
                            ->get()
                            ->mapWithKeys(fn (Reward $reward) => [
                                $reward->id => "{$reward->title} ({$reward->required_credits} credits)",
                            ])
                    )
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $record) {
                        if ($state && $record) {
                            $reward = Reward::find($state);
                            if ($reward) {
                                $currentBalance = $record->current_balance;
                                $remaining = $currentBalance - $reward->required_credits;
                                $set('confirmation_message', $remaining >= 0
                                    ? "This will cost {$reward->required_credits} credits. Remaining balance: {$remaining} credits."
                                    : "Insufficient balance. Required: {$reward->required_credits} credits, Current: {$currentBalance} credits."
                                );
                            }
                        }
                    }),
                TextEntry::make('confirmation_message')
                    ->label('')
                    ->getStateUsing(function (callable $get, Customer $record): ?string {
                        $rewardId = $get('reward_id');

                        $reward = Reward::query()->find($rewardId);

                        if (! $reward instanceof Reward) {
                            return null;
                        }

                        $currentBalance = $record->current_balance;
                        $remaining = $currentBalance - $reward->required_credits;

                        return $remaining >= 0
                            ? "This will cost {$reward->required_credits} credits. Remaining balance: {$remaining} credits."
                            : "⚠️ Insufficient balance. Required: {$reward->required_credits} credits, Current: {$currentBalance} credits.";
                    })
                    ->visible(fn ($get): bool => ! empty($get('reward_id'))),
            ])
            ->action(function (array $data, Customer $record, RedeemReward $redeemReward) {
                $reward = Reward::query()->find($data['reward_id']);

                if (! $reward instanceof Reward) {
                    Notification::make()
                        ->title('Invalid Reward')
                        ->body('The selected reward is not available.')
                        ->danger()
                        ->send();

                    return;
                }

                try {
                    $redeemReward(
                        administrator: auth()->user(),
                        customer: $record,
                        reward: $reward,
                    );

                    Notification::make()
                        ->title('Reward Redeemed')
                        ->body("Successfully redeemed '{$reward->title}'. {$reward->required_credits} credits deducted.")
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error Redeeming Reward')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
