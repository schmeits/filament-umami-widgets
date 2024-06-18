<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableUrls extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_url';

    public function getData(): array
    {
        return FilamentUmami::metricsUrl($this->getFilter());
    }
}
