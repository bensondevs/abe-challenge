<?php

namespace App\Filament\Resources\CustomerBonusPrograms\Tables;

use App\Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerBonusProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('credit_amount')
                    ->label('Credit Amount')
                    ->sortable()
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => number_format($state).' credits'),
                IconColumn::make('active')
                    ->boolean()
                    ->sortable(),
                DateColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                DateColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ]);
    }
}
