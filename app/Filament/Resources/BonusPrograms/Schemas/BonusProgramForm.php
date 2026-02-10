<?php

namespace App\Filament\Resources\BonusPrograms\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BonusProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->nullable(),
                TextInput::make('credit_amount')
                    ->label('Credit Amount')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Toggle::make('active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
