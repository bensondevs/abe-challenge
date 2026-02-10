<?php

namespace App\Filament\Resources\BonusPrograms\Pages;

use App\Filament\Resources\BonusPrograms\BonusProgramResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBonusProgram extends ViewRecord
{
    protected static string $resource = BonusProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
