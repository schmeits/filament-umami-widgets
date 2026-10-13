<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableQuery.php
 *
 * Table widget with the Umami query metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableQuery extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_query';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsQuery($this->getFilter());
    }
}
