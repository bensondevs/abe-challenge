<?php

namespace App\Models;

use App\Enums\Transaction\Type;
use App\Observers\CreditTransactionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Customer $customer
 */
#[ObservedBy(CreditTransactionObserver::class)]
class CreditTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'user_id',
        'type',
        'reason',
        'amount',
        'bonus_program_id',
        'reward_id',
    ];

    public function casts(): array
    {
        return [
            'amount' => 'integer',
            'type' => Type::class,
        ];
    }

    public function administrator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::updating(function (CreditTransaction $transaction): void {
            if ($transaction->isDirty('amount')) {
                $transaction->validateBalanceAfterUpdate();
            }
        });
    }

    /**
     * Validate that updating this transaction won't cause negative balance.
     *
     * @throws \Exception if balance would go negative
     */
    private function validateBalanceAfterUpdate(): void
    {
        $customer = $this->customer;
        if (! $customer) {
            return;
        }

        $oldAmount = $this->getOriginal('amount') ?? 0;
        $newAmount = $this->amount;
        $amountDifference = $newAmount - $oldAmount;

        $currentBalance = $customer->getCreditBalanceCalculator()->calculate(force: true);
        $newBalance = $currentBalance + $amountDifference;

        if ($newBalance < 0) {
            throw new \Exception("Cannot update transaction. New balance would be {$newBalance} credits, which is negative.");
        }
    }
}
