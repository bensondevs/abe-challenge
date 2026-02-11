<?php

namespace App\Filament\Resources\Customers\Actions;

use App\Actions\AdjustCredits;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class AdjustCreditsAction
{
    public static function make(): Action
    {
        return Action::make('adjustCredits')
            ->label('Adjust credits')
            ->icon(Heroicon::OutlinedBanknotes)
            ->slideOver()
            ->schema([
                Radio::make('is_deduction')
                    ->label('Transaction Type')
                    ->options([
                        false => 'Addition',
                        true => 'Deduction',
                    ])
                    ->required()
                    ->default(false)
                    ->reactive()
                    ->inline(),
                TextInput::make('amount')
                    ->label('Amount')
                    ->prefix(fn (Get $get) => $get('is_deduction') ? '-' : '+')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(
                        fn (Get $get, Customer $record) => $get('is_deduction')
                            ? $record->current_balance
                            : PHP_INT_MAX,
                    )
                    ->helperText('Enter the number of credits')
                    ->live(onBlur: true),
                Textarea::make('reason')
                    ->label('Reason')
                    ->required()
                    ->rows(3)
                    ->helperText('Provide a reason for this transaction'),
            ])
            ->action(function (array $data, Customer $record, AdjustCredits $adjustCredits): void {
                try {
                    $amount = (int) $data['amount'];
                    $isDeduction = (bool) $data['is_deduction'];

                    $adjustCredits(
                        administrator: auth()->user(),
                        customer: $record,
                        amount: $isDeduction ? -$amount : $amount,
                        reason: $data['reason'],
                    );

                    Notification::make()
                        ->title('Transaction Created')
                        ->body(
                            $isDeduction
                                ? "Successfully deducted {$amount} credits."
                                : "Successfully added {$amount} credits."
                        )
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error Creating Transaction')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            })
            ->modalWidth(Width::ExtraLarge)
            ->modalFooterActionsAlignment(Alignment::Center)
            ->successNotificationTitle('Transaction completed successfully');
    }
}
