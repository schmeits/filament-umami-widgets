<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableOs extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_os';

    protected bool $limitResults = true;

    public function getData(): array
    {
        return FilamentUmami::metricsOs($this->getFilter());
    }
}
