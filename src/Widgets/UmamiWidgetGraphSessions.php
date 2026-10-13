<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetGraphSessions.php
 *
 * Chart widget with the Umami sessions per day.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetGraphSessions extends UmamiBaseChartWidget
{
    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = '1';

    protected string $id = 'chart_sessions';

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $days = FilamentUmami::pageViewsAndSessions()['sessions'];

        return [
            'datasets' => [
                [
                    'label' => trans("filament-umami-widgets::translations.widget.$this->id.dataset_label"),
                    'data' => array_values($days),
                ],
            ],
            'labels' => $this->dayLabels($days),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
