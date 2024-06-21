<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableGroupedPages extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_pages';

    protected bool $limitResults = true;

    public function getOptions(): array
    {
        return [
            'url' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.url"),
            'title' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.title"),
        ];
    }

    public function getHeaders(): array
    {
        $headers = collect();

        $headers->push([
            'name' => 'metric',
            'label' => trans("filament-umami-widgets::translations.widget.metrics_$this->option.headers.metric"),
            'width' => '90%',
        ]);

        $headers->push($this->getDefaultCountHeaderItem());

        return $headers->toArray();
    }

    public function getData(): array
    {
        $metricsFunction = match ($this->option) {
            'title' => 'metricsTitle',
            default => 'metricsPages'
        };

        return FilamentUmami::$metricsFunction($this->getFilter());
    }
}
