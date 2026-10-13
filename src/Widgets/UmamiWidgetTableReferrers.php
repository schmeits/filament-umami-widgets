<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableReferrers.php
 *
 * Table widget with the Umami referrers metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableReferrers extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_referrer';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsReferrer($this->getFilter());
    }
}
