<?php

namespace Schmeits\FilamentUmami\Widgets;

use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;

class UmamiWidgetStatsGrouped extends UmamiBaseStatsWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        $count = count($this->getCachedStats());

        if ($count < 3) {
            return 3;
        }

        if (($count % 3) !== 1) {
            return 3;
        }

        return 4;
    }

    protected function getStats(): array
    {
        $widgets = FilamentUmamiPlugin::get()->getWidgets();

        return collect($widgets)->map(function ($widget) {
            if ($widget instanceof UmamiStatsWidgets) {
                return $this->createStat($widget);
            }
        })->toArray();
    }
}
