<?php

namespace App\Filament\Resources\Rewards\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RewardForm
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
                TextInput::make('required_credits')
                    ->label('Required Credits')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Toggle::make('active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
