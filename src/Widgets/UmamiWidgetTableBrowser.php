<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableBrowser.php
 *
 * Table widget with the Umami browser metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableBrowser extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_browser';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsBrowser($this->getFilter());
    }
}
