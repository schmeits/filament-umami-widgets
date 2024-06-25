<?php

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Support\RawJs;
use Illuminate\Support\Carbon;
use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetGraphEvents extends UmamiBaseChartWidget
{
    protected static string $color = 'success';

    protected int | string | array $columnSpan = '1';

    protected string $id = 'chart_events';

    protected function getColors(): array
    {
        return [
            '#FF0000',
            '#0FF000',
            '#00FF00',
            '#000FF0',
            '#0000FF',
        ];
    }

    public function currentColor(int $id): string
    {
        $colors = $this->getColors();

        return $colors[$id % count($colors)];
    }

    protected function getData(): array
    {
        $events = FilamentUmami::websiteEvents($this->getFilter());

        $uniqueTypes = collect($events)
            ->flatMap(function ($dateEvents) {
                return collect($dateEvents);  // Flatten collection
            })
            ->pluck('type')  // collect 'type' fields
            ->unique()  // Filter unique types
            ->values();  // Get only the values

        $datasets = [];
        foreach ($uniqueTypes as $id => $type) {
            $datasets[] = [
                'label' => $type,
                'data' => collect($events)
                    ->map(function ($dateEvents) use ($type) {
                        if (empty($dateEvents)) {
                            return [0];
                        }

                        return collect($dateEvents)
                            ->reject(function ($event) use ($type) {
                                return $event['type'] !== $type;
                            })
                            ->map(function ($event) {
                                return $event['count'] ?? 0;
                            })->all();
                    })->flatten()->all(),
                'borderColor' => $this->currentColor($id),
                'backgroundColor' => $this->currentColor($id),
            ];

        }

        return [
            'datasets' => $datasets,
            'labels' => collect($events)
                ->keys()
                ->map(fn ($item) => Carbon::make($item)->format(trans('filament-umami-widgets::translations.widget.global.date_format')))
                ->toArray(),
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
                    display: true,
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
