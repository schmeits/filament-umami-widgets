<?php

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
