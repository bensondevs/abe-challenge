<?php

namespace App\Filament\Resources\CustomerRewards\Pages;

use App\Filament\Resources\CustomerRewards\CustomerRewardResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomerRewards extends ListRecords
{
    protected static string $resource = CustomerRewardResource::class;
}

