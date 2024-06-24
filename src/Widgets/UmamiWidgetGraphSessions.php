<?php

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Schmeits\FilamentUmami\Facades\FilamentUmami;
use Schmeits\FilamentUmami\Traits\GetFilterForWidget;

class UmamiWidgetGraphSessions extends ChartWidget
{
    use GetFilterForWidget;
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    //protected static string $view = 'filament-umami-widgets::graph-widget';

    protected int | string | array $columnSpan = '1';

    public function getHeading(): string | Htmlable | null
    {
        return trans('filament-umami-widgets::translations.widget.chart_sessions.heading');
    }

    protected function getData(): array
    {
        $results = FilamentUmami::pageViewsAndSessions();

        $pageviews = collect($results['sessions'] ?? [])->reverse();

        return [
            'datasets' => [
                [
                    'label' => trans('filament-umami-widgets::translations.widget.chart_sessions.dataset_label'),
                    'data' => $pageviews->pluck('y')->toArray(),
                ],
            ],
            'labels' => $pageviews->pluck('x')->map(fn ($item) => Carbon::make($item)->format('d-m-Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                        stepSize: 1,
                    },
                },
            },
        }
    JS);
    }
}
