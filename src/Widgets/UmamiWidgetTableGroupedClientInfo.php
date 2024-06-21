<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableGroupedClientInfo extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_client_info';

    protected bool $limitResults = true;

    public function getOptions(): array
    {
        return [
            'browser' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.browser"),
            'os' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.os"),
            'device' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.device"),
            'screen' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.screen"),
            'language' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.language"),
        ];
    }

    public function getHeaders(): array
    {
        $headers = collect();

        $metric = $this->getDefaultMetricHeaderItem('60%');
        $metric['label'] = trans("filament-umami-widgets::translations.widget.metrics_$this->option.headers.metric");
        $headers->push($metric);

        $headers->push($this->getDefaultCountHeaderItem());

        return $headers->toArray();
    }

    public function getData(): array
    {
        $metricsFunction = match ($this->option) {
            'os' => 'metricsOs',
            'device' => 'metricsDevice',
            'screen' => 'metricsScreen',
            'language' => 'metricsLanguage',
            default => 'metricsBrowser'
        };

        return FilamentUmami::$metricsFunction($this->getFilter());
    }
}
