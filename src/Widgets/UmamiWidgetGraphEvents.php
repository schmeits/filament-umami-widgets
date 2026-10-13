<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetGraphEvents.php
 *
 * Chart widget with the Umami events per day.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Support\RawJs;
use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetGraphEvents extends UmamiBaseChartWidget
{
    protected string $color = 'success';

    protected int | string | array $columnSpan = '1';

    protected string $id = 'chart_events';

    /**
     * Okabe-Ito palette: colours that stay distinguishable with the common forms
     * of colour blindness. The legend is shown as well, so the event name is
     * always readable as text and colour is never the only signal.
     *
     * @return list<string>
     */
    protected function getColors(): array
    {
        return [
            '#0072B2',
            '#E69F00',
            '#009E73',
            '#CC79A7',
            '#56B4E9',
            '#D55E00',
            '#F0E442',
            '#999999',
        ];
    }

    public function currentColor(int $id): string
    {
        $colors = $this->getColors();

        return $colors[$id % count($colors)];
    }

    /**
     * One dataset per event name with one value per day. A day without that
     * event counts as 0, so every dataset lines up with the date labels.
     *
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $events = FilamentUmami::websiteEvents($this->getFilter());

        $types = [];

        foreach ($events as $dayEvents) {
            foreach ($dayEvents as $event) {
                $types[$event['type']] = true;
            }
        }

        $datasets = [];

        foreach (array_keys($types) as $index => $type) {
            $data = [];

            foreach ($events as $dayEvents) {
                $count = 0;

                foreach ($dayEvents as $event) {
                    if ($event['type'] === (string) $type) {
                        $count += $event['count'];
                    }
                }

                $data[] = $count;
            }

            $datasets[] = [
                'label' => (string) $type,
                'data' => $data,
                'borderColor' => $this->currentColor($index),
                'backgroundColor' => $this->currentColor($index),
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $this->dayLabels($events),
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
