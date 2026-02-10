<?php

namespace App\Filament\Resources\Customers\Infolists;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Customer Name'),
                TextEntry::make('user.name')
                    ->label('User Account')
                    ->placeholder('No user account linked'),
                TextEntry::make('current_balance')
                    ->label('Current Credit Balance')
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => number_format($state) . ' credits'),
                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ]);
    }
}


