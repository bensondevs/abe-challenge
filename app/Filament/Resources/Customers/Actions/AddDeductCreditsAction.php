<?php

namespace App\Filament\Resources\Customers\Actions;

use App\Enums\Transaction\Type;
use App\Models\CreditTransaction;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class AddDeductCreditsAction
{
    public static function make(): Action
    {
        return Action::make('addDeductCredits')
            ->label('Add/Deduct Credits')
            ->icon('heroicon-o-banknotes')
            ->color('primary')
            ->schema([
                Radio::make('transaction_type')
                    ->label('Transaction Type')
                    ->options([
                        'add' => 'Add Credits',
                        'deduct' => 'Deduct Credits',
                    ])
                    ->required()
                    ->default('add')
                    ->inline(),
                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->helperText('Enter the number of credits'),
                Textarea::make('reason')
                    ->label('Reason')
                    ->required()
                    ->rows(3)
                    ->helperText('Provide a reason for this transaction'),
            ])
            ->action(function (array $data, Customer $record): void {
                $amount = (int) $data['amount'];
                $transactionType = $data['transaction_type'];
                $finalAmount = $transactionType === 'deduct' ? -$amount : $amount;
                $currentBalance = $record->current_balance;

                // Validate that balance won't go negative
                if ($currentBalance + $finalAmount < 0) {
                    Notification::make()
                        ->title('Insufficient Balance')
                        ->body("Cannot deduct {$amount} credits. Current balance: {$currentBalance} credits.")
                        ->danger()
                        ->send();

                    return;
                }

                CreditTransaction::create([
                    'customer_id' => $record->id,
                    'user_id' => auth()->id(),
                    'type' => Type::Manual,
                    'amount' => $finalAmount,
                    'reason' => $data['reason'],
                ]);

                Notification::make()
                    ->title('Transaction Created')
                    ->body(
                        $transactionType === 'add'
                            ? "Successfully added {$amount} credits."
                            : "Successfully deducted {$amount} credits."
                    )
                    ->success()
                    ->send();
            })
            ->successNotificationTitle('Transaction completed successfully');
    }
}
