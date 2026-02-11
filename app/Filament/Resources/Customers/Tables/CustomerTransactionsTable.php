<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Enums\Transaction\Type;
use App\Filament\Tables\Columns\DateColumn;
use App\Filament\Tables\Filters\DateRangeFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['administrator']))
            ->defaultSort('created_at', 'desc')
            ->columns([
                DateColumn::make('created_at')
                    ->label('Date/Time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (Type $state): string => match ($state) {
                        Type::Manual => 'gray',
                        Type::Bonus => 'success',
                        Type::Reward => 'warning',
                    })
                    ->formatStateUsing(fn (Type $state): string => $state->getLabel())
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => ($state >= 0 ? '+' : '').number_format($state).' credits')
                    ->color(fn (int $state): string => $state >= 0 ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Reason')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('administrator.name')
                    ->label('Admin')
                    ->placeholder('System')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Transaction Type')
                    ->options([
                        Type::Manual->value => Type::Manual->getLabel(),
                        Type::Bonus->value => Type::Bonus->getLabel(),
                        Type::Reward->value => Type::Reward->getLabel(),
                    ]),

                DateRangeFilter::make('created_at')
                    ->label('Date Range'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
