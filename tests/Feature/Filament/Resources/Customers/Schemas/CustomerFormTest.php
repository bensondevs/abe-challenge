<?php

namespace Tests\Feature\Filament\Resources\Customers\Schemas;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

// Create Page Tests
it('can render customer create form', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->assertSuccessful()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('email')
        ->assertFormFieldExists('password')
        ->assertFormFieldExists('password_confirmation');
});

it('can create customer with valid data', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Customer::class, [
        'name' => 'Test Customer',
        'email' => 'test@example.com',
    ]);
});

it('requires name field on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required'])
        ->assertNotNotified();
});

it('requires email field on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'required'])
        ->assertNotNotified();
});

it('requires password field on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['password' => 'required'])
        ->assertNotNotified();
});

it('requires password_confirmation field on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['password_confirmation' => 'required'])
        ->assertNotNotified();
});

it('validates email format on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'email'])
        ->assertNotNotified();
});

it('validates email uniqueness on create', function () {
    $user = User::factory()->create();
    Customer::factory()->create(['email' => 'existing@example.com']);

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'unique'])
        ->assertNotNotified();
});

it('validates password minimum length on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])
        ->call('create')
        ->assertHasFormErrors(['password' => 'min'])
        ->assertNotNotified();
});

it('validates password confirmation matches password on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ])
        ->call('create')
        ->assertHasFormErrors(['password_confirmation' => 'same'])
        ->assertNotNotified();
});

it('validates name max length on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => str_repeat('a', 256),
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'max'])
        ->assertNotNotified();
});

it('validates email max length on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'email' => str_repeat('a', 250).'@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'max'])
        ->assertNotNotified();
});

// Edit Page Tests
it('can render customer edit form', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->assertSuccessful()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('email')
        ->assertFormFieldExists('password')
        ->assertFormFieldExists('password_confirmation');
});

it('can update customer with valid data', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => 'Updated Customer',
            'email' => 'updated@example.com',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($customer->fresh()->name)->toBe('Updated Customer');
    expect($customer->fresh()->email)->toBe('updated@example.com');
});

it('requires name field on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => '',
            'email' => $customer->email,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required'])
        ->assertNotNotified();
});

it('requires email field on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => $customer->name,
            'email' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'required'])
        ->assertNotNotified();
});

it('password field is optional on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();
    $originalPassword = $customer->password;

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => $customer->email,
            // password fields omitted
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($customer->fresh()->password)->toBe($originalPassword);
});

it('password confirmation required only if password is filled on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => $customer->name,
            'email' => $customer->email,
            'password' => 'newpassword123',
            // password_confirmation omitted
        ])
        ->call('save')
        ->assertHasFormErrors(['password_confirmation' => 'required'])
        ->assertNotNotified();
});

it('validates email format on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => $customer->name,
            'email' => 'invalid-email',
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'email'])
        ->assertNotNotified();
});

it('validates email uniqueness ignoring current record on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);
    $otherCustomer = Customer::factory()->create(['email' => 'other@example.com']);

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => $customer->name,
            'email' => 'other@example.com', // duplicate
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'unique'])
        ->assertNotNotified();
});

it('can update customer with same email on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => 'customer@example.com', // same email
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();
});

it('validates password minimum length if provided on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => $customer->name,
            'email' => $customer->email,
            'password' => 'short',
            'password_confirmation' => 'short',
        ])
        ->call('save')
        ->assertHasFormErrors(['password' => 'min'])
        ->assertNotNotified();
});

it('validates password confirmation matches password if password provided on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => $customer->name,
            'email' => $customer->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'different123',
        ])
        ->call('save')
        ->assertHasFormErrors(['password_confirmation' => 'same'])
        ->assertNotNotified();
});

it('can update customer without changing password on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();
    $originalPassword = $customer->password;

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($customer->fresh()->name)->toBe('Updated Name');
    expect($customer->fresh()->password)->toBe($originalPassword);
});

it('validates name max length on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => str_repeat('a', 256),
            'email' => $customer->email,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'max'])
        ->assertNotNotified();
});

it('validates email max length on edit', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    actingAs($user);

    Livewire::test(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'name' => $customer->name,
            'email' => str_repeat('a', 250).'@example.com',
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'max'])
        ->assertNotNotified();
});
