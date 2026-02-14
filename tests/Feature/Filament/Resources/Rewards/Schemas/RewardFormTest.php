<?php

namespace Tests\Feature\Filament\Resources\Rewards\Schemas;

use App\Filament\Resources\Rewards\Pages\CreateReward;
use App\Filament\Resources\Rewards\Pages\EditReward;
use App\Models\Reward;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

// Create Page Tests
it('can render reward create form', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->assertSuccessful()
        ->assertFormFieldExists('title')
        ->assertFormFieldExists('description')
        ->assertFormFieldExists('required_credits')
        ->assertFormFieldExists('active');
});

it('can create reward with valid data', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->fillForm([
            'title' => 'Test Reward',
            'description' => 'Test description',
            'required_credits' => 50,
            'active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Reward::class, [
        'title' => 'Test Reward',
        'description' => 'Test description',
        'required_credits' => 50,
        'active' => true,
    ]);
});

it('requires title field on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->fillForm([
            'required_credits' => 50,
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required'])
        ->assertNotNotified();
});

it('requires required_credits field on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->fillForm([
            'title' => 'Test Reward',
        ])
        ->call('create')
        ->assertHasFormErrors(['required_credits' => 'required'])
        ->assertNotNotified();
});

it('validates required_credits is numeric on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->fillForm([
            'title' => 'Test Reward',
            'required_credits' => 'not-a-number',
        ])
        ->call('create')
        ->assertHasFormErrors(['required_credits' => 'numeric'])
        ->assertNotNotified();
});

it('validates required_credits minimum value on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->fillForm([
            'title' => 'Test Reward',
            'required_credits' => 0,
        ])
        ->call('create')
        ->assertHasFormErrors(['required_credits' => 'min'])
        ->assertNotNotified();
});

it('validates title max length on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->fillForm([
            'title' => str_repeat('a', 256),
            'required_credits' => 50,
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'max'])
        ->assertNotNotified();
});

it('description field is optional on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->fillForm([
            'title' => 'Test Reward',
            'required_credits' => 50,
            // description omitted
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();
});

it('active field defaults to true on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->fillForm([
            'title' => 'Test Reward',
            'required_credits' => 50,
            // active omitted, should default to true
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $reward = Reward::where('title', 'Test Reward')->first();
    expect($reward->active)->toBeTrue();
});

it('can create reward with description on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->fillForm([
            'title' => 'Test Reward',
            'description' => 'Test description',
            'required_credits' => 50,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas(Reward::class, [
        'title' => 'Test Reward',
        'description' => 'Test description',
    ]);
});

// Edit Page Tests
it('can render reward edit form', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->assertSuccessful()
        ->assertFormFieldExists('title')
        ->assertFormFieldExists('description')
        ->assertFormFieldExists('required_credits')
        ->assertFormFieldExists('active');
});

it('can update reward with valid data', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->fillForm([
            'title' => 'Updated Reward',
            'description' => 'Updated description',
            'required_credits' => 100,
            'active' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($reward->fresh()->title)->toBe('Updated Reward');
    expect($reward->fresh()->required_credits)->toBe(100);
});

it('requires title field on edit', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->fillForm([
            'title' => '',
            'required_credits' => $reward->required_credits,
        ])
        ->call('save')
        ->assertHasFormErrors(['title' => 'required'])
        ->assertNotNotified();
});

it('requires required_credits field on edit', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->fillForm([
            'title' => $reward->title,
            'required_credits' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['required_credits' => 'required'])
        ->assertNotNotified();
});

it('validates required_credits is numeric on edit', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->fillForm([
            'title' => $reward->title,
            'required_credits' => 'not-a-number',
        ])
        ->call('save')
        ->assertHasFormErrors(['required_credits' => 'numeric'])
        ->assertNotNotified();
});

it('validates required_credits minimum value on edit', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->fillForm([
            'title' => $reward->title,
            'required_credits' => 0,
        ])
        ->call('save')
        ->assertHasFormErrors(['required_credits' => 'min'])
        ->assertNotNotified();
});

it('validates title max length on edit', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->fillForm([
            'title' => str_repeat('a', 256),
            'required_credits' => $reward->required_credits,
        ])
        ->call('save')
        ->assertHasFormErrors(['title' => 'max'])
        ->assertNotNotified();
});

it('description field is optional on edit', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create(['description' => 'Original description']);

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->fillForm([
            'title' => $reward->title,
            'required_credits' => $reward->required_credits,
            // description omitted - nullable field
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();
});

it('can update reward with description on edit', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->fillForm([
            'title' => $reward->title,
            'description' => 'Updated description',
            'required_credits' => $reward->required_credits,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($reward->fresh()->description)->toBe('Updated description');
});

it('can update reward without description on edit', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create(['description' => 'Original description']);

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->fillForm([
            'title' => 'Updated Title',
            'required_credits' => $reward->required_credits,
            // description omitted
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($reward->fresh()->title)->toBe('Updated Title');
});
