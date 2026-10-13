<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableCity.php
 *
 * Table widget with the Umami city metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableCity extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_city';

    /**
     * @return list<array<string, mixed>>
     */
    public function getHeaders(): array
    {
        return [
            $this->getDefaultMetricHeaderItem('60%'),
            [
                'name' => 'country',
                'label' => trans("filament-umami-widgets::translations.widget.$this->id.headers.country"),
                'width' => '30%',
            ],
            $this->getDefaultCountHeaderItem(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsCity($this->getFilter());
    }
}
