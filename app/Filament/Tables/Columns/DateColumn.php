<?php

namespace App\Filament\Tables\Columns;

use Carbon\CarbonInterface;
use Filament\Tables\Columns\TextColumn;

class DateColumn extends TextColumn
{
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->date(format: 'd M Y')
            ->description(fn (?CarbonInterface $state) => $state->diffForHumans())
            ->wrap();
    }
}
