<?php

namespace Schmeits\FilamentUmami\Concerns;

use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

trait HasFilter
{
    use \Filament\Pages\Dashboard\Concerns\HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(trans('filament-umami-widgets::translations.filter.title'))
                    ->description(trans('filament-umami-widgets::translations.filter.description'))
                    ->schema([
                        DateRangePicker::make('date_range')
                            ->label(trans('filament-umami-widgets::translations.filter.select_range'))
                            ->columnSpan('full')
                            ->displayFormat(trans('filament-umami-widgets::translations.filter.date_format'))
                            ->format('d-m-Y')
                            ->startDate(now()->subDays(30), true)
                            ->endDate(now(), true)
                            ->maxDate(now()),
                    ])
                    ->columns(2),
            ]);
    }
}
