<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetStatsTotalTime.php
 *
 * Stats widget with the Umami total time number.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;

class UmamiWidgetStatsTotalTime extends UmamiBaseStatsWidget
{
    protected int | string | array $columnSpan = '1';

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        return [
            $this->createStat(UmamiStatsWidgets::WIDGET_TOTAL_TIME),
        ];
    }
}
