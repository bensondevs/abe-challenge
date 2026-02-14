<?php

namespace App\Actions;

use App\Enums\Transaction\Type;
use App\Events\Reward\RewardRedeemedByCustomer;
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
     * @throws \Exception|\Throwable if reward is not active or insufficient balance
     */
    public function __invoke(
        Customer $customer,
        Reward $reward,
        ?User $administrator = null,
    ): CreditTransaction {
        return DB::transaction(function () use ($administrator, $customer, $reward): CreditTransaction {
            if (! $reward->isActive()) {
                throw new \Exception('Reward is not active');
            }

            $lockedCustomer = Customer::query()
                ->lockForUpdate()
                ->find($customer->getKey());

            if (! $lockedCustomer instanceof Customer) {
                throw new \Exception('Customer not found');
            }

            $currentBalance = $lockedCustomer->getCreditBalanceCalculator()->calculate(force: true);

            if ($currentBalance < $reward->required_credits) {
                throw new \Exception("Cannot redeem '{$reward->title}'. Required: {$reward->required_credits} credits, Current: {$currentBalance} credits.");
            }

            $transaction = new CreditTransaction;
            $transaction->type = Type::Reward;
            $transaction->amount = -$reward->required_credits;
            $transaction->reason = "Redeemed: {$reward->title}";

            $transaction->customer()->associate($lockedCustomer);

            if ($administrator) {
                $transaction->administrator()->associate($administrator);
            }

            $transaction->save();

            if (! $administrator instanceof User) {
                event(new RewardRedeemedByCustomer($customer, $reward));
            }

            return $transaction;
        });
    }
}
