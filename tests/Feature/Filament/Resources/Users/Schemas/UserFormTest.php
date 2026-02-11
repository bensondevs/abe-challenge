<?php

namespace Tests\Feature\Filament\Resources\Users\Schemas;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

// Create Page Tests
it('can render user create form', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateUser::class)
        ->assertSuccessful()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('email')
        ->assertFormFieldExists('password')
        ->assertFormFieldExists('password_confirmation');
});

it('can create user with valid data', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(User::class, [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

it('requires name field on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateUser::class)
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

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
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

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
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

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
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

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
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
    User::factory()->create(['email' => 'existing@example.com']);

    actingAs($user);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
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

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
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

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
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

    Livewire::test(CreateUser::class)
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

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => str_repeat('a', 250).'@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'max'])
        ->assertNotNotified();
});

// Edit Page Tests
it('can render user edit form', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->assertSuccessful()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('email')
        ->assertFormFieldExists('password')
        ->assertFormFieldExists('password_confirmation');
});

it('can update user with valid data', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($editUser->fresh()->name)->toBe('Updated User');
    expect($editUser->fresh()->email)->toBe('updated@example.com');
});

it('requires name field on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => '',
            'email' => $editUser->email,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required'])
        ->assertNotNotified();
});

it('requires email field on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => $editUser->name,
            'email' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'required'])
        ->assertNotNotified();
});

it('password field is optional on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();
    $originalPassword = $editUser->password;

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => $editUser->email,
            // password fields omitted
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($editUser->fresh()->password)->toBe($originalPassword);
});

it('password confirmation required only if password is filled on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => $editUser->name,
            'email' => $editUser->email,
            'password' => 'newpassword123',
            // password_confirmation omitted
        ])
        ->call('save')
        ->assertHasFormErrors(['password_confirmation' => 'required'])
        ->assertNotNotified();
});

it('validates email format on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => $editUser->name,
            'email' => 'invalid-email',
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'email'])
        ->assertNotNotified();
});

it('validates email uniqueness ignoring current record on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create(['email' => 'user@example.com']);
    $otherUser = User::factory()->create(['email' => 'other@example.com']);

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => $editUser->name,
            'email' => 'other@example.com', // duplicate
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'unique'])
        ->assertNotNotified();
});

it('can update user with same email on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create(['email' => 'user@example.com']);

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => 'user@example.com', // same email
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();
});

it('validates password minimum length if provided on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => $editUser->name,
            'email' => $editUser->email,
            'password' => 'short',
            'password_confirmation' => 'short',
        ])
        ->call('save')
        ->assertHasFormErrors(['password' => 'min'])
        ->assertNotNotified();
});

it('validates password confirmation matches password if password provided on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => $editUser->name,
            'email' => $editUser->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'different123',
        ])
        ->call('save')
        ->assertHasFormErrors(['password_confirmation' => 'same'])
        ->assertNotNotified();
});

it('can update user without changing password on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();
    $originalPassword = $editUser->password;

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($editUser->fresh()->name)->toBe('Updated Name');
    expect($editUser->fresh()->password)->toBe($originalPassword);
});

it('validates name max length on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => str_repeat('a', 256),
            'email' => $editUser->email,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'max'])
        ->assertNotNotified();
});

it('validates email max length on edit', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->fillForm([
            'name' => $editUser->name,
            'email' => str_repeat('a', 250).'@example.com',
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'max'])
        ->assertNotNotified();
});

