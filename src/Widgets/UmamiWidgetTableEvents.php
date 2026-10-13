<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableEvents.php
 *
 * Table widget with the Umami events metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableEvents extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_event';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsEvent($this->getFilter());
    }
}
