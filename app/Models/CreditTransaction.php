<?php

namespace App\Models;

use App\Enums\Transaction\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditTransaction extends Model
{
    public function casts(): array
    {
        return [
            'amount' => 'integer',
            'type' => Type::class,
        ];
    }

    public function administrator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
