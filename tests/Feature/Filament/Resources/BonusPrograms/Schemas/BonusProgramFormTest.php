<?php

namespace Tests\Feature\Filament\Resources\BonusPrograms\Schemas;

use App\Filament\Resources\BonusPrograms\Pages\CreateBonusProgram;
use App\Filament\Resources\BonusPrograms\Pages\EditBonusProgram;
use App\Models\BonusProgram;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

// Create Page Tests
it('can render bonus program create form', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->assertSuccessful()
        ->assertFormFieldExists('title')
        ->assertFormFieldExists('description')
        ->assertFormFieldExists('credit_amount')
        ->assertFormFieldExists('active');
});

it('can create bonus program with valid data', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->fillForm([
            'title' => 'Test Bonus Program',
            'description' => 'Test description',
            'credit_amount' => 100,
            'active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(BonusProgram::class, [
        'title' => 'Test Bonus Program',
        'description' => 'Test description',
        'credit_amount' => 100,
        'active' => true,
    ]);
});

it('requires title field on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->fillForm([
            'credit_amount' => 100,
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required'])
        ->assertNotNotified();
});

it('requires credit_amount field on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->fillForm([
            'title' => 'Test Bonus Program',
        ])
        ->call('create')
        ->assertHasFormErrors(['credit_amount' => 'required'])
        ->assertNotNotified();
});

it('validates credit_amount is numeric on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->fillForm([
            'title' => 'Test Bonus Program',
            'credit_amount' => 'not-a-number',
        ])
        ->call('create')
        ->assertHasFormErrors(['credit_amount' => 'numeric'])
        ->assertNotNotified();
});

it('validates credit_amount minimum value on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->fillForm([
            'title' => 'Test Bonus Program',
            'credit_amount' => 0,
        ])
        ->call('create')
        ->assertHasFormErrors(['credit_amount' => 'min'])
        ->assertNotNotified();
});

it('validates title max length on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->fillForm([
            'title' => str_repeat('a', 256),
            'credit_amount' => 100,
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'max'])
        ->assertNotNotified();
});

it('description field is optional on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->fillForm([
            'title' => 'Test Bonus Program',
            'credit_amount' => 100,
            // description omitted
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();
});

it('active field defaults to true on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->fillForm([
            'title' => 'Test Bonus Program',
            'credit_amount' => 100,
            // active omitted, should default to true
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $bonusProgram = BonusProgram::where('title', 'Test Bonus Program')->first();
    expect($bonusProgram->active)->toBeTrue();
});

it('can create bonus program with description on create', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->fillForm([
            'title' => 'Test Bonus Program',
            'description' => 'Test description',
            'credit_amount' => 100,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas(BonusProgram::class, [
        'title' => 'Test Bonus Program',
        'description' => 'Test description',
    ]);
});

// Edit Page Tests
it('can render bonus program edit form', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->assertSuccessful()
        ->assertFormFieldExists('title')
        ->assertFormFieldExists('description')
        ->assertFormFieldExists('credit_amount')
        ->assertFormFieldExists('active');
});

it('can update bonus program with valid data', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->fillForm([
            'title' => 'Updated Bonus Program',
            'description' => 'Updated description',
            'credit_amount' => 200,
            'active' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($bonusProgram->fresh()->title)->toBe('Updated Bonus Program');
    expect($bonusProgram->fresh()->credit_amount)->toBe(200);
});

it('requires title field on edit', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->fillForm([
            'title' => '',
            'credit_amount' => $bonusProgram->credit_amount,
        ])
        ->call('save')
        ->assertHasFormErrors(['title' => 'required'])
        ->assertNotNotified();
});

it('requires credit_amount field on edit', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->fillForm([
            'title' => $bonusProgram->title,
            'credit_amount' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['credit_amount' => 'required'])
        ->assertNotNotified();
});

it('validates credit_amount is numeric on edit', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->fillForm([
            'title' => $bonusProgram->title,
            'credit_amount' => 'not-a-number',
        ])
        ->call('save')
        ->assertHasFormErrors(['credit_amount' => 'numeric'])
        ->assertNotNotified();
});

it('validates credit_amount minimum value on edit', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->fillForm([
            'title' => $bonusProgram->title,
            'credit_amount' => 0,
        ])
        ->call('save')
        ->assertHasFormErrors(['credit_amount' => 'min'])
        ->assertNotNotified();
});

it('validates title max length on edit', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->fillForm([
            'title' => str_repeat('a', 256),
            'credit_amount' => $bonusProgram->credit_amount,
        ])
        ->call('save')
        ->assertHasFormErrors(['title' => 'max'])
        ->assertNotNotified();
});

it('description field is optional on edit', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create(['description' => 'Original description']);

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->fillForm([
            'title' => $bonusProgram->title,
            'credit_amount' => $bonusProgram->credit_amount,
            // description omitted - nullable field
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();
});

it('can update bonus program with description on edit', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->fillForm([
            'title' => $bonusProgram->title,
            'description' => 'Updated description',
            'credit_amount' => $bonusProgram->credit_amount,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($bonusProgram->fresh()->description)->toBe('Updated description');
});

it('can update bonus program without description on edit', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create(['description' => 'Original description']);

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->fillForm([
            'title' => 'Updated Title',
            'credit_amount' => $bonusProgram->credit_amount,
            // description omitted
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($bonusProgram->fresh()->title)->toBe('Updated Title');
});
