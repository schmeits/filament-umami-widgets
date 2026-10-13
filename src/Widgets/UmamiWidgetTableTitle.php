<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableTitle.php
 *
 * Table widget with the Umami title metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableTitle extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_title';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsTitle($this->getFilter());
    }
}
