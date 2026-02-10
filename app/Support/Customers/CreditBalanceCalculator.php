<?php

namespace App\Support\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\Cache;

readonly class CreditBalanceCalculator
{
    public function __construct(
        private Customer $customer
    ) {}

    /**
     * Create a new credit balance auditor instance.
     */
    public static function for(Customer $customer): self
    {
        return new self($customer);
    }

    /**
     * Generate a unique cache key for the customer's credit balance.
     */
    private function getCacheKey(): string
    {
        $customerId = $this->customer->getKey();

        return "customer.$customerId.credit_balance";
    }

    /**
     * Calculate the customer's credit balance.
     * Uses cache when available, otherwise calculates from transactions.
     */
    public function calculate(bool $force = false): int
    {
        if ($force) {
            $this->invalidateCachedBalance();
        }

        return Cache::remember(
            key: $this->getCacheKey(),
            ttl: now()->addHour(),
            callback: fn (): int => $this->customer
                ->creditTransactions()
                ->sum(column: 'amount'),
        );
    }

    /**
     * Invalidate the cached credit balance for this customer.
     * Call this when credit transactions are created/updated/deleted.
     */
    public function invalidateCachedBalance(): void
    {
        Cache::forget($this->getCacheKey());
    }
}
