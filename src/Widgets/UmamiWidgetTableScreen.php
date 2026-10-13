<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableScreen.php
 *
 * Table widget with the Umami screen metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableScreen extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_screen';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsScreen($this->getFilter());
    }
}
