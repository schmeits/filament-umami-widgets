<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetStatsPageViews.php
 *
 * Stats widget with the Umami page views number.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;

class UmamiWidgetStatsPageViews extends UmamiBaseStatsWidget
{
    protected int | string | array $columnSpan = '1';

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        return [
            $this->createStat(UmamiStatsWidgets::WIDGET_PAGEVIEWS),
        ];
    }
}
