<?php

namespace App\Filament\Resources\CustomerBonusPrograms\Pages;

use App\Filament\Resources\CustomerBonusPrograms\CustomerBonusProgramResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomerBonusPrograms extends ListRecords
{
    protected static string $resource = CustomerBonusProgramResource::class;
}

