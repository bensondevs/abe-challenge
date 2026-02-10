<?php

namespace Database\Seeders;

use App\Models\BonusProgram;
use App\Models\CreditTransaction;
use App\Models\Customer;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'administrator@abe-challenge.com',
        ]);

        Customer::factory()->count(100)
            ->create()
            ->each(
                fn (Customer $customer) => CreditTransaction::factory()
                    ->count(fake()->numberBetween(3, 10))
                    ->for($customer, 'customer')
                    ->create(),
            );

        BonusProgram::factory()->count(3)->active()->create();
        BonusProgram::factory()->count(2)->inactive()->create();

        Reward::factory()->count(3)->active()->create();
        Reward::factory()->count(2)->inactive()->create();
    }
}
