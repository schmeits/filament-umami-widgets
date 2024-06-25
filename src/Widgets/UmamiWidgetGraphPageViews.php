<?php

namespace Schmeits\FilamentUmami\Widgets;

use Illuminate\Support\Carbon;
use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetGraphPageViews extends UmamiBaseChartWidget
{
    protected static string $color = 'info';

    protected int | string | array $columnSpan = '1';

    protected string $id = 'chart_pageviews';

    protected function getData(): array
    {
        $results = FilamentUmami::pageViewsAndSessions();

        $pageviews = collect($results['pageviews'] ?? []);

        return [
            'datasets' => [
                [
                    'label' => trans("filament-umami-widgets::translations.widget.$this->id.dataset_label"),
                    'data' => $pageviews->values()->toArray(),
                ],
            ],
            'labels' => $pageviews
                ->keys()
                ->map(fn ($item) => Carbon::make($item)->format(trans('filament-umami-widgets::translations.widget.global.date_format')))
                ->toArray(),
        ];
    }
}
