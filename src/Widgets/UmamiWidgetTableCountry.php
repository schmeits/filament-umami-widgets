<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableCountry.php
 *
 * Table widget with the Umami country metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableCountry extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_country';

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsCountry($this->getFilter());
    }
}
