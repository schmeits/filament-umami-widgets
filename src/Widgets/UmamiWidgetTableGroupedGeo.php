<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableGroupedGeo extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_geo';

    protected bool $limitResults = true;

    public function getOptions(): array
    {
        return [
            'country' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.country"),
            'region' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.region"),
            'city' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.city"),
        ];
    }

    public function getHeaders(): array
    {
        $headers = collect();

        $headers->push([
            'name' => 'metric',
            'label' => trans("filament-umami-widgets::translations.widget.metrics_$this->option.headers.metric"),
            'width' => $this->option === 'country' ? '90%' : '60%',
        ]);

        if ($this->option !== 'country') {
            $headers->push([
                'name' => 'country',
                'label' => trans("filament-umami-widgets::translations.widget.metrics_$this->option.headers.country"),
                'width' => '30%',
            ]);
        }

        $headers->push($this->getDefaultCountHeaderItem());

        return $headers->toArray();
    }

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
