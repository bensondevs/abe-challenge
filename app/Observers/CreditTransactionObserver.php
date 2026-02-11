<?php

namespace App\Observers;

use App\Models\CreditTransaction;

class CreditTransactionObserver
{
    /**
     * Handle the CreditTransaction "created" event.
     */
    public function created(CreditTransaction $creditTransaction): void
    {
        $this->invalidateBalance($creditTransaction);
    }

    /**
     * Handle the CreditTransaction "updated" event.
     */
    public function updated(CreditTransaction $creditTransaction): void
    {
        if ($creditTransaction->wasChanged('amount')) {
            $this->invalidateBalance($creditTransaction);
        }
    }

    /**
     * Handle the CreditTransaction "deleted" event.
     */
    public function deleted(CreditTransaction $creditTransaction): void
    {
        $this->invalidateBalance($creditTransaction);
    }

    /**
     * Handle the CreditTransaction "restored" event.
     */
    public function restored(CreditTransaction $creditTransaction): void
    {
        $this->invalidateBalance($creditTransaction);
    }

    /**
     * Handle the CreditTransaction "force deleted" event.
     */
    public function forceDeleted(CreditTransaction $creditTransaction): void
    {
        $this->invalidateBalance($creditTransaction);
    }

    /**
     * Invalidate the cached balance for the transaction's customer.
     */
    private function invalidateBalance(CreditTransaction $creditTransaction): void
    {
        $creditTransaction->customer
            ?->getCreditBalanceCalculator()
            ->invalidateCachedBalance();
    }
}
