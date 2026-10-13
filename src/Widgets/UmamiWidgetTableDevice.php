<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableDevice.php
 *
 * Table widget with the Umami device metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableDevice extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_device';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsDevice($this->getFilter());
    }
}
