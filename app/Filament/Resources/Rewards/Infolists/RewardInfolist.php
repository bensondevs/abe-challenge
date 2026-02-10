<?php

namespace App\Filament\Resources\Rewards\Infolists;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RewardInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Title'),
                TextEntry::make('description')
                    ->label('Description')
                    ->placeholder('No description provided'),
                TextEntry::make('required_credits')
                    ->label('Required Credits')
                    ->numeric(),
                IconEntry::make('active')
                    ->label('Active Status')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ]);
    }
}
