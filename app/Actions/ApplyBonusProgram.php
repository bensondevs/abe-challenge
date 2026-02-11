<?php

namespace App\Actions;

use App\Enums\Transaction\Type;
use App\Models\BonusProgram;
use App\Models\CreditTransaction;
use App\Models\Customer;
use App\Models\User;

class ApplyBonusProgram
{
    public function __invoke(
        ?User $administrator,
        Customer $customer,
        BonusProgram $bonusProgram,
        string $reason,
    ): CreditTransaction {
        $transaction = new CreditTransaction;
        $transaction->type = Type::Bonus;
        $transaction->amount = $bonusProgram->credit_amount;
        $transaction->reason = $reason;

        $transaction->customer()->associate($customer);
        if ($administrator) {
            $transaction->administrator()->associate($administrator);
        }

        $transaction->save();

        return $transaction;
    }
}
