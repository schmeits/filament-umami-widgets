<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableCity extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_city';

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

    public function getData(): array
    {
        return FilamentUmami::metricsCity($this->getFilter());
    }
}
