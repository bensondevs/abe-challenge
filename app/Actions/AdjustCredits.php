<?php

namespace App\Actions;

use App\Enums\Transaction\Type;
use App\Models\CreditTransaction;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdjustCredits
{
    /**
     * Adjust customer credits (add or deduct).
     *
     * @throws \Exception if balance would go negative
     */
    public function __invoke(
        User $administrator,
        Customer $customer,
        int $amount,
        string $reason
    ): CreditTransaction {
        return DB::transaction(function () use ($administrator, $customer, $amount, $reason): CreditTransaction {
            // Lock the customer row to prevent concurrent modifications
            $lockedCustomer = Customer::lockForUpdate()->find($customer->id);
            if (! $lockedCustomer) {
                throw new \Exception('Customer not found');
            }

            $currentBalance = $lockedCustomer
                ->getCreditBalanceCalculator()
                ->calculate(force: true);

            if ($currentBalance + $amount < 0) {
                $absoluteAmount = abs($amount);
                throw new \Exception("Cannot deduct {$absoluteAmount} credits. Current balance: {$currentBalance} credits.");
            }

            $transaction = new CreditTransaction;
            $transaction->type = Type::Manual;
            $transaction->amount = $amount;
            $transaction->reason = $reason;

            $transaction->customer()->associate($lockedCustomer);
            $transaction->administrator()->associate($administrator);

            $transaction->save();

            return $transaction;
        });
    }
}
