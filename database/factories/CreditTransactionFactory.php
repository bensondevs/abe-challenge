<?php

namespace Database\Factories;

use App\Enums\Transaction\Type;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditTransaction>
 */
class CreditTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->numberBetween(10, 1000);
        $finalAmount = fake()->boolean(70) ? $amount : -$amount;

        return [
            'customer_id' => Customer::factory(),
            'type' => Type::random(),
            'amount' => $finalAmount,
            'reason' => fake()->sentence(),
        ];
    }
}
