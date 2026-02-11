<?php

namespace App\Filament\Resources\Customers\Actions;

use App\Enums\Transaction\Type;
use App\Models\CreditTransaction;
use App\Models\Customer;
use App\Models\Reward;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class RedeemRewardAction
{
    public static function make(): Action
    {
        return Action::make('redeemReward')
            ->label('Redeem Reward')
            ->icon('heroicon-o-star')
            ->color('warning')
            ->schema([
                Select::make('reward_id')
                    ->label('Reward')
                    ->options(
                        Reward::query()
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
                \Filament\Forms\Components\Placeholder::make('confirmation_message')
                    ->label('')
                    ->content(function ($get, $record): ?string {
                        $rewardId = $get('reward_id');
                        if (! $rewardId) {
                            return null;
                        }

                        $reward = Reward::find($rewardId);
                        if (! $reward) {
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
            ->action(function (array $data, Customer $record): void {
                $reward = Reward::find($data['reward_id']);

                if (! $reward || ! $reward->active) {
                    Notification::make()
                        ->title('Invalid Reward')
                        ->body('The selected reward is not available.')
                        ->danger()
                        ->send();

                    return;
                }

                $currentBalance = $record->current_balance;

                // Validate sufficient balance
                if ($currentBalance < $reward->required_credits) {
                    Notification::make()
                        ->title('Insufficient Balance')
                        ->body("Cannot redeem '{$reward->title}'. Required: {$reward->required_credits} credits, Current: {$currentBalance} credits.")
                        ->danger()
                        ->send();

                    return;
                }

                CreditTransaction::create([
                    'customer_id' => $record->id,
                    'user_id' => auth()->id(),
                    'type' => Type::Reward,
                    'amount' => -$reward->required_credits,
                    'reason' => "Redeemed: {$reward->title}",
                    'reward_id' => $reward->id,
                ]);

                Notification::make()
                    ->title('Reward Redeemed')
                    ->body("Successfully redeemed '{$reward->title}'. {$reward->required_credits} credits deducted.")
                    ->success()
                    ->send();
            })
            ->successNotificationTitle('Reward redeemed successfully');
    }
}
