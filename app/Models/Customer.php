<?php

namespace App\Models;

use App\Support\Customers\CreditBalanceCalculator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Get the user account associated with the customer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the credit transactions for the customer.
     */
    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function currentBalance(): Attribute
    {
        return Attribute::get(
            fn () => $this->getCreditBalanceCalculator()->calculate(),
        );
    }

    public function getCreditBalanceCalculator(): CreditBalanceCalculator
    {
        return CreditBalanceCalculator::for($this);
    }
}
