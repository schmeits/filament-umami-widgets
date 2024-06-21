<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableBrowser extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_browser';

    protected bool $limitResults = true;

    public function getData(): array
    {
        return FilamentUmami::metricsBrowser($this->getFilter());
    }
}
