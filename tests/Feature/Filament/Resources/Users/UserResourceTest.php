<?php

namespace Tests\Feature\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('can access user list page', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(ListUsers::class)
        ->assertSuccessful();
});

it('can access user create page', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateUser::class)
        ->assertSuccessful();
});

it('can access user view page', function () {
    $user = User::factory()->create();
    $viewUser = User::factory()->create();

    actingAs($user);

    Livewire::test(ViewUser::class, ['record' => $viewUser->id])
        ->assertSuccessful();
});

it('can access user edit page', function () {
    $user = User::factory()->create();
    $editUser = User::factory()->create();

    actingAs($user);

    Livewire::test(EditUser::class, ['record' => $editUser->id])
        ->assertSuccessful();
});
