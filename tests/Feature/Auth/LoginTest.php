<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

it('admin can authenticate with seeded credentials', function () {
    $user = User::factory()->create([
        'email' => 'administrator@abe-challenge.com',
        'password' => Hash::make('password'),
    ]);

    $authenticated = Auth::attempt([
        'email' => 'administrator@abe-challenge.com',
        'password' => 'password',
    ]);

    expect($authenticated)->toBeTrue();
    expect(Auth::user()->id)->toBe($user->id);
});

it('customer can authenticate with seeded credentials', function () {
    $customer = Customer::factory()->create([
        'email' => 'customer@abe-challenge.com',
        'password' => Hash::make('password'),
    ]);

    $authenticated = Auth::guard('customer')->attempt([
        'email' => 'customer@abe-challenge.com',
        'password' => 'password',
    ]);

    expect($authenticated)->toBeTrue();
    expect(Auth::guard('customer')->user()->id)->toBe($customer->id);
});
