<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableGroupedPages.php
 *
 * Table widget with Umami metrics grouped by pages, switchable with a select.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableGroupedPages extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_pages';

    protected bool $limitResults = true;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            'url' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.url"),
            'title' => trans("filament-umami-widgets::translations.widget.{$this->id}.options.title"),
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
            'width' => '90%',
        ];

        $headers[] = $this->getDefaultCountHeaderItem();

        return $headers;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        $metricsFunction = match ($this->option) {
            'title' => 'metricsTitle',
            default => 'metricsPages'
        };

        return FilamentUmami::$metricsFunction($this->getFilter());
    }
}
