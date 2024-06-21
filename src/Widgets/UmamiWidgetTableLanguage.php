<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableLanguage extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_language';

    protected bool $limitResults = true;

    public function getData(): array
    {
        return FilamentUmami::metricsLanguage($this->getFilter());
    }
}
