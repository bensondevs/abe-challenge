<?php

namespace Tests\Feature\Filament\Resources\Rewards;

use App\Filament\Resources\Rewards\Pages\CreateReward;
use App\Filament\Resources\Rewards\Pages\EditReward;
use App\Filament\Resources\Rewards\Pages\ListRewards;
use App\Filament\Resources\Rewards\Pages\ViewReward;
use App\Models\Reward;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('can access reward list page', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(ListRewards::class)
        ->assertSuccessful();
});

it('can access reward create page', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(CreateReward::class)
        ->assertSuccessful();
});

it('can access reward view page', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(ViewReward::class, ['record' => $reward->id])
        ->assertSuccessful();
});

it('can access reward edit page', function () {
    $user = User::factory()->create();
    $reward = Reward::factory()->create();

    actingAs($user);

    Livewire::test(EditReward::class, ['record' => $reward->id])
        ->assertSuccessful();
});
