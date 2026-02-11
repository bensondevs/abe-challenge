<?php

namespace App\Filament\Resources\CustomerBonusPrograms;

use App\Filament\Resources\BonusPrograms\Infolists\BonusProgramInfolist;
use App\Filament\Resources\CustomerBonusPrograms\Pages\ListCustomerBonusPrograms;
use App\Filament\Resources\CustomerBonusPrograms\Pages\ViewCustomerBonusProgram;
use App\Filament\Resources\CustomerBonusPrograms\Tables\CustomerBonusProgramsTable;
use App\Models\BonusProgram;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerBonusProgramResource extends Resource
{
    protected static ?string $model = BonusProgram::class;

    protected static ?string $navigationLabel = 'Bonus Programs';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return CustomerBonusProgramsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BonusProgramInfolist::configure($schema);
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
            'index' => ListCustomerBonusPrograms::route('/'),
            'view' => ViewCustomerBonusProgram::route('/{record}'),
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

