<?php

namespace App\Filament\Resources\CustomerRewards;

use App\Filament\Resources\CustomerRewards\Pages\ListCustomerRewards;
use App\Filament\Resources\CustomerRewards\Pages\ViewCustomerReward;
use App\Filament\Resources\CustomerRewards\Tables\CustomerRewardsTable;
use App\Filament\Resources\Rewards\Infolists\RewardInfolist;
use App\Models\Reward;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerRewardResource extends Resource
{
    protected static ?string $model = Reward::class;

    protected static ?string $navigationLabel = 'Rewards';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return CustomerRewardsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RewardInfolist::configure($schema);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('active', true)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerRewards::route('/'),
            'view' => ViewCustomerReward::route('/{record}'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->where('active', true)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'customer';
    }
}
