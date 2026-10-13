<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiWidgetStatsGrouped.php
 *
 * Stats widget that shows several Umami numbers side by side. Which numbers is
 * set on the plugin with widgetsForGroupedStats().
 */

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
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

    /**
     * @return list<Stat>
     */
    protected function getStats(): array
    {
        $plugin = FilamentUmamiPlugin::current() ?? FilamentUmamiPlugin::make();

        $stats = [];

        foreach ($plugin->getWidgets() as $widget) {
            $stats[] = $this->createStat($widget);
        }

        return $stats;
    }
}
