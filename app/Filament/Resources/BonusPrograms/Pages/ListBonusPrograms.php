<?php

namespace App\Filament\Resources\BonusPrograms\Pages;

use App\Filament\Resources\BonusPrograms\BonusProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBonusPrograms extends ListRecords
{
    protected static string $resource = BonusProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
