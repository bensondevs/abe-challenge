<?php

namespace App\Filament\Widgets;

use App\Enums\Transaction\Type;
use App\Filament\Tables\Columns\DateColumn;
use App\Models\CreditTransaction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CustomerRecentTransactionsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getListeners(): array
    {
        return ['reward-redeemed' => '$refresh'];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => CreditTransaction::query()
                    ->where('customer_id', auth('customer')->id())
                    ->latest()
                    ->limit(10)
            )
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
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->heading('Recent Transactions');
    }
}
