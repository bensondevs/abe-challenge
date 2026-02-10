<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'user_id' => null,
        ];
    }

    /**
     * Indicate that the customer should have a user account.
     */
    public function withUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory(),
        ]);
    }
}

