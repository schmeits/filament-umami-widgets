<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableDevice extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_device';

    protected bool $limitResults = true;

    public function getData(): array
    {
        return FilamentUmami::metricsDevice($this->getFilter());
    }
}
