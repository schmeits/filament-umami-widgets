<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableUrls.php
 *
 * Table widget with the Umami urls metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableUrls extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_url';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsPages($this->getFilter());
    }
}
