<?php

namespace App\Filament\Resources\BonusPrograms;

use App\Filament\Resources\BonusPrograms\Infolists\BonusProgramInfolist;
use App\Filament\Resources\BonusPrograms\Pages\CreateBonusProgram;
use App\Filament\Resources\BonusPrograms\Pages\EditBonusProgram;
use App\Filament\Resources\BonusPrograms\Pages\ListBonusPrograms;
use App\Filament\Resources\BonusPrograms\Pages\ViewBonusProgram;
use App\Filament\Resources\BonusPrograms\Schemas\BonusProgramForm;
use App\Filament\Resources\BonusPrograms\Tables\BonusProgramsTable;
use App\Models\BonusProgram;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BonusProgramResource extends Resource
{
    protected static ?string $model = BonusProgram::class;

    protected static ?string $navigationLabel = 'Bonus Programs';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    public static function form(Schema $schema): Schema
    {
        return BonusProgramForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BonusProgramsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BonusProgramInfolist::configure($schema);
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
            'index' => ListBonusPrograms::route('/'),
            'create' => CreateBonusProgram::route('/create'),
            'view' => ViewBonusProgram::route('/{record}'),
            'edit' => EditBonusProgram::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
