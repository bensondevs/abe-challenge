<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;

class AdministratorDashboard extends Dashboard
{
    protected static string|null|BackedEnum $navigationIcon = Heroicon::OutlinedHome;
}

