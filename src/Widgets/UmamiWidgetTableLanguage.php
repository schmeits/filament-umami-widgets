<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetTableLanguage.php
 *
 * Table widget with the Umami language metrics.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Facades\FilamentUmami;

class UmamiWidgetTableLanguage extends UmamiBaseTableWidget
{
    protected int | string | array $columnSpan = '1';

    public string $id = 'metrics_language';

    protected bool $limitResults = true;

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return FilamentUmami::metricsLanguage($this->getFilter());
    }
}
