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
}
