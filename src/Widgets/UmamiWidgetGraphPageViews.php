<?php

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Schmeits\FilamentUmami\Facades\FilamentUmami;
use Schmeits\FilamentUmami\Traits\GetFilterForWidget;

class UmamiWidgetGraphPageViews extends ChartWidget
{
    use GetFilterForWidget;
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    protected static string $color = 'info';

    protected int | string | array $columnSpan = '1';

    public function getHeading(): string | Htmlable | null
    {
        return trans('filament-umami-widgets::translations.widget.chart_pageviews.heading');
    }

    protected function getData(): array
    {
        $results = FilamentUmami::pageViewsAndSessions();

        $pageviews = collect($results['pageviews'] ?? []);

        return [
            'datasets' => [
                [
                    'label' => trans('filament-umami-widgets::translations.widget.chart_pageviews.dataset_label'),
                    'data' => $pageviews->values()->toArray(),
                ],
            ],
            'labels' => $pageviews
                ->keys()
                ->map(fn ($item) => Carbon::make($item)->format(trans('filament-umami-widgets::translations.widget.chart_pageviews.date_format')))
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            plugins: {
                legend : {
                    display: false,
                },
            },
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true,
                    },
                },
            },
        }
    JS);
    }
}
