<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\Customers\Tables\CustomerTransactionsTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CreditTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'creditTransactions';

    public function getListeners(): array
    {
        return ['customer-updated' => '$refresh'];
    }

    public function table(Table $table): Table
    {
        return CustomerTransactionsTable::configure($table);
    }
}
