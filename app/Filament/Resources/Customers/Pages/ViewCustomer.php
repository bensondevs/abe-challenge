<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\Actions\AddDeductCreditsAction;
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

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getActions(): array
    {
        return [
            AddDeductCreditsAction::make(),
            ApplyBonusProgramAction::make(),
            RedeemRewardAction::make(),
        ];
    }
}

