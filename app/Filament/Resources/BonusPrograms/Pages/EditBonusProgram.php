<?php

namespace App\Filament\Resources\BonusPrograms\Pages;

use App\Filament\Resources\BonusPrograms\BonusProgramResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBonusProgram extends EditRecord
{
    protected static string $resource = BonusProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
