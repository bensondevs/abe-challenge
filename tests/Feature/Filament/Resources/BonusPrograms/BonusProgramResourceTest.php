<?php

namespace Tests\Feature\Filament\Resources\BonusPrograms;

use App\Filament\Resources\BonusPrograms\Pages\CreateBonusProgram;
use App\Filament\Resources\BonusPrograms\Pages\EditBonusProgram;
use App\Filament\Resources\BonusPrograms\Pages\ListBonusPrograms;
use App\Filament\Resources\BonusPrograms\Pages\ViewBonusProgram;
use App\Models\BonusProgram;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('can access bonus program list page', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(ListBonusPrograms::class)
        ->assertSuccessful();
});

it('can access bonus program create page', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateBonusProgram::class)
        ->assertSuccessful();
});

it('can access bonus program view page', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(ViewBonusProgram::class, ['record' => $bonusProgram->id])
        ->assertSuccessful();
});

it('can access bonus program edit page', function () {
    $user = User::factory()->create();
    $bonusProgram = BonusProgram::factory()->create();

    actingAs($user);

    Livewire::test(EditBonusProgram::class, ['record' => $bonusProgram->id])
        ->assertSuccessful();
});
