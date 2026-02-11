<?php

namespace App\Filament\Tables\Filters;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class DateRangeFilter extends Filter
{
    protected \Closure|string $tableColumn = 'created_at';

    public function setUp(): void
    {
        parent::setUp();

        $this->columns();

        $this->schema([
            DatePicker::make('from'),
            DatePicker::make('to'),
        ]);

        $this->modifyQueryUsing(
            fn (Builder $query, array $state) => $query
                ->when(
                    filled($state['from'] ?? null),
                    fn (Builder $query) => $query->whereDate(
                        $this->getTableColumn(),
                        '>=',
                        $state['from'],
                    ),
                )
                ->when(
                    filled($state['to'] ?? null),
                    fn (Builder $query) => $query->whereDate(
                        $this->getTableColumn(),
                        '<=',
                        $state['to'],
                    ),
                ),
        );
    }

    public function getTableColumn(): string
    {
        return $this->evaluate($this->tableColumn);
    }

    public function tableColumn(string $column): static
    {
        $this->tableColumn = $column;

        return $this;
    }
}
