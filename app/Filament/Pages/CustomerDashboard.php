<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;

class CustomerDashboard extends Dashboard
{
    protected static string|null|BackedEnum $navigationIcon = Heroicon::OutlinedHome;

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'customer';
    }
}

