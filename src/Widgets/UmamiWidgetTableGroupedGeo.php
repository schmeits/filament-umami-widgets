<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableGroupedGeo.php
 *
 * Table widget with Umami metrics grouped by geo, switchable with a select.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableGroupedGeo extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_geo';

    protected bool $limitResults = true;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            'country' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.country"),
            'region' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.region"),
            'city' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.city"),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getHeaders(): array
    {
        $headers = [];

        $headers[] = [
            'name' => 'metric',
            'label' => trans("filament-umami-widgets::translations.widget.metrics_$this->option.headers.metric"),
            'width' => $this->option === 'country' ? '90%' : '60%',
        ];

        if ($this->option !== 'country') {
            $headers[] = [
                'name' => 'country',
                'label' => trans("filament-umami-widgets::translations.widget.metrics_$this->option.headers.country"),
                'width' => '30%',
            ];
        }

        $headers[] = $this->getDefaultCountHeaderItem();

        return $headers;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        $metricsFunction = match ($this->option) {
            'region' => 'metricsRegion',
            'city' => 'metricsCity',
            default => 'metricsCountry'
        };

        return FilamentUmami::$metricsFunction($this->getFilter());
    }
}
