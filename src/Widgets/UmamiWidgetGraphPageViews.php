<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetGraphPageViews.php
 *
 * Chart widget with the Umami page views per day.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetGraphPageViews extends UmamiBaseChartWidget
{
    protected string $color = 'info';

    protected int | string | array $columnSpan = '1';

    protected string $id = 'chart_pageviews';

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $days = FilamentUmami::pageViewsAndSessions()['pageviews'];

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
}
