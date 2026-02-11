<?php

namespace App\Filament\Forms\Components;

use App\Models\BonusProgram;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class BonusProgramSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Bonus Program');

        $this->allowHtml();

        $this->options(
            fn () => $this->getOptionsQuery()
                ->get()
                ->mapWithKeys(fn (BonusProgram $bonusProgram) => $this->formatOption($bonusProgram)),
        );

        $this->searchable();

        $this->getSearchResultsUsing(
            fn (?string $search) => $this->getOptionsQuery()
                ->when(
                    filled($search),
                    fn (Builder $query) => $query->where(
                        fn (Builder $query) => $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%"),
                    ),
                )
                ->get()
                ->mapWithKeys(fn (BonusProgram $bonusProgram) => $this->formatOption($bonusProgram)),
        );

        $this->reactive();

        $this->helperText(
            fn (?int $state) => $state
                ? BonusProgram::query()->where('id', $state)->value('bonus_programs.description')
                : '',
        );
    }

    protected function getOptionsQuery(): Builder
    {
        return BonusProgram::query()
            ->select('id', 'title', 'credit_amount', 'active')
            ->where('active', true);
    }

    protected function formatOption(BonusProgram $bonusProgram): array
    {
        return [
            $bonusProgram->getKey() => $bonusProgram->title .'<br />'
                . '<span class="fi-sc-text">+'
                . $bonusProgram->credit_amount . ' credits'
                .'</span>',
        ];
    }
}
