<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableEvents extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_event';

    protected bool $limitResults = true;

    public function getData(): array
    {
        return FilamentUmami::metricsEvent($this->getFilter());
    }
}
