<?php

namespace App\Filament\Widgets;

use App\Actions\RedeemReward;
use App\Models\Reward;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CustomerRewardsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => Reward::query()->where('active', true))
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('required_credits')
                    ->label('Required Credits')
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => number_format($state).' credits'),
            ])
            ->paginated(false)
            ->heading('Available Rewards');
    }
}

