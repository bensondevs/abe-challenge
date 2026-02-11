<?php

namespace App\Filament\Resources\Customers\Actions;

use App\Enums\Transaction\Type;
use App\Models\BonusProgram;
use App\Models\CreditTransaction;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class ApplyBonusProgramAction
{
    public static function make(): Action
    {
        return Action::make('applyBonusProgram')
            ->label('Apply Bonus Program')
            ->icon('heroicon-o-gift')
            ->color('success')
            ->schema([
                Select::make('bonus_program_id')
                    ->label('Bonus Program')
                    ->options(
                        BonusProgram::query()
                            ->where('active', true)
                            ->pluck('title', 'id')
                    )
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $bonusProgram = BonusProgram::find($state);
                            if ($bonusProgram) {
                                $set('reason', $bonusProgram->description);
                            }
                        }
                    }),
                Textarea::make('reason')
                    ->label('Reason')
                    ->required()
                    ->rows(3)
                    ->helperText('Reason for applying this bonus program'),
            ])
            ->action(function (array $data, Customer $record): void {
                $bonusProgram = BonusProgram::find($data['bonus_program_id']);

                if (! $bonusProgram || ! $bonusProgram->active) {
                    Notification::make()
                        ->title('Invalid Bonus Program')
                        ->body('The selected bonus program is not available.')
                        ->danger()
                        ->send();

                    return;
                }

                CreditTransaction::create([
                    'customer_id' => $record->id,
                    'user_id' => auth()->id(),
                    'type' => Type::Bonus,
                    'amount' => $bonusProgram->credit_amount,
                    'reason' => $data['reason'] ?: $bonusProgram->description,
                    'bonus_program_id' => $bonusProgram->id,
                ]);

                Notification::make()
                    ->title('Bonus Applied')
                    ->body("Successfully applied '{$bonusProgram->title}' bonus program. {$bonusProgram->credit_amount} credits added.")
                    ->success()
                    ->send();
            })
            ->successNotificationTitle('Bonus program applied successfully');
    }
}
