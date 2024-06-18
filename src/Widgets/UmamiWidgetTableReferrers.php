<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableReferrers extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_referrer';

    public function getData(): array
    {
        return FilamentUmami::metricsReferrer($this->getFilter());
    }
}
