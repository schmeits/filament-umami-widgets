<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableScreen extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_screen';

    protected bool $limitResults = true;

    public function getData(): array
    {
        return FilamentUmami::metricsScreen($this->getFilter());
    }
}
