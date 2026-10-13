<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableRegion.php
 *
 * Table widget with the Umami region metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableRegion extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_region';

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
        return FilamentUmami::metricsRegion($this->getFilter());
    }
}
