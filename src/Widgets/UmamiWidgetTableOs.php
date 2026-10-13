<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableOs.php
 *
 * Table widget with the Umami os metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableOs extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_os';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsOs($this->getFilter());
    }
}
