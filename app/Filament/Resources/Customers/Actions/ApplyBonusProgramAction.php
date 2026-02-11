<?php

namespace App\Filament\Resources\Customers\Actions;

use App\Actions\ApplyBonusProgram;
use App\Forms\Components\BonusProgramSelect;
use App\Models\BonusProgram;
use App\Models\Customer;
use Filament\Actions\Action;
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
                BonusProgramSelect::make('bonus_program_id')
                    ->afterStateUpdated(
                        fn (callable $get, callable $set) => blank($get('reason'))
                            ? $set('reason', 'Applying bonus program')
                            : '',
                    ),
                Textarea::make('reason')
                    ->label('Reason')
                    ->required()
                    ->rows(3)
                    ->helperText('Reason for applying this bonus program'),
            ])
            ->action(function (?array $data, Customer $record, ApplyBonusProgram $applyBonusProgram): void {
                $bonusProgram = BonusProgram::query()->find($data['bonus_program_id']);

                if (! $bonusProgram instanceof BonusProgram) {
                    Notification::make()
                        ->title('Invalid Bonus Program')
                        ->body('The selected bonus program is not available.')
                        ->danger()
                        ->send();

                    return;
                }

                try {
                    $applyBonusProgram(
                        administrator: auth()->user(),
                        customer: $record,
                        bonusProgram: $bonusProgram,
                        reason: $data['reason'],
                    );

                    Notification::make()
                        ->title('Bonus Applied')
                        ->body("Successfully applied '{$bonusProgram->title}' bonus program. {$bonusProgram->credit_amount} credits added.")
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error Applying Bonus')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
