<?php

namespace App\Filament\Resources\CustomerTransactions;

use App\Filament\Resources\CustomerTransactions\Pages\ListCustomerTransactions;
use App\Filament\Resources\CustomerTransactions\Tables\CustomerTransactionsTable;
use App\Models\CreditTransaction;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerTransactionResource extends Resource
{
    protected static ?string $model = CreditTransaction::class;

    protected static ?string $navigationLabel = 'Transactions';

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
            ->where('customer_id', auth('customer')->id());
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Hidden from navigation, accessible via dashboard widgets
    }

    public static function table(Table $table): Table
    {
        return CustomerTransactionsTable::configure($table);
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
            'index' => ListCustomerTransactions::route('/'),
        ];
    }
}
