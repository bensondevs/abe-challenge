<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\Actions\AdjustCreditsAction;
use App\Filament\Resources\Customers\Actions\ApplyBonusProgramAction;
use App\Filament\Resources\Customers\Actions\RedeemRewardAction;
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    public function getListeners(): array
    {
        return ['customer-updated' => '$refresh'];
    }

    protected function getHeaderActions(): array
    {
        return [
            AdjustCreditsAction::make()
                ->after(fn () => $this->dispatch('customer-updated')),
            ApplyBonusProgramAction::make()
                ->after(fn () => $this->dispatch('customer-updated')),
            RedeemRewardAction::make()
                ->after(fn () => $this->dispatch('customer-updated')),
            EditAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
