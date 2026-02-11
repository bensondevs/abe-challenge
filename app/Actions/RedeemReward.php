<?php

namespace App\Actions;

use App\Enums\Transaction\Type;
use App\Models\CreditTransaction;
use App\Models\Customer;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RedeemReward
{
    /**
     * Redeem a reward for a customer.
     *
     * @throws \Exception if reward is not active or insufficient balance
     */
    public function __invoke(
        User $administrator,
        Customer $customer,
        Reward $reward
    ): CreditTransaction {
        return DB::transaction(function () use ($administrator, $customer, $reward): CreditTransaction {
            // Validate reward is active
            if (! $reward->active) {
                throw new \Exception('Reward is not active');
            }

            // Lock the customer row to prevent concurrent modifications
            $lockedCustomer = Customer::lockForUpdate()->find($customer->id);
            if (! $lockedCustomer) {
                throw new \Exception('Customer not found');
            }

            // Validate sufficient balance
            $currentBalance = $lockedCustomer->getCreditBalanceCalculator()->calculate(force: true);
            if ($currentBalance < $reward->required_credits) {
                throw new \Exception("Cannot redeem '{$reward->title}'. Required: {$reward->required_credits} credits, Current: {$currentBalance} credits.");
            }

            // Create credit transaction using manual assignment
            $transaction = new CreditTransaction;
            $transaction->type = Type::Reward;
            $transaction->amount = -$reward->required_credits;
            $transaction->reason = "Redeemed: {$reward->title}";

            $transaction->customer()->associate($lockedCustomer);
            $transaction->administrator()->associate($administrator);

            $transaction->save();

            return $transaction;
        });
    }
}
