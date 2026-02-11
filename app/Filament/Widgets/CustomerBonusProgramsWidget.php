<?php

namespace App\Filament\Widgets;

use App\Models\BonusProgram;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CustomerBonusProgramsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => BonusProgram::query()->where('active', true))
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('credit_amount')
                    ->label('Credit Amount')
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => number_format($state).' credits'),
            ])
            ->paginated(false)
            ->heading('Available Bonus Programs');
    }
}
