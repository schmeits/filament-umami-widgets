<?php

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;
use Schmeits\FilamentUmami\Facades\FilamentUmami;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;
use Schmeits\FilamentUmami\Traits\GetFilterForWidget;
use Schmeits\FilamentUmami\Traits\HasDescription;

abstract class UmamiBaseStatsWidget extends BaseWidget
{
    use GetFilterForWidget;
    use HasDescription;
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -9;

    protected string $id = '';

    protected function getColumns(): int
    {
        return 1;
    }

    public function getPollingInterval(): ?string
    {
        return FilamentUmamiPlugin::get()->getPollingInterval();
    }

    protected function getStats(): array
    {
        return [];
    }

    public function createStat(UmamiStatsWidgets $widget): Stat
    {
        $filter = $this->getFilter();

        $value = match ($widget) {
            UmamiStatsWidgets::WIDGET_LIVE => FilamentUmami::activeVisitors($filter),
            UmamiStatsWidgets::WIDGET_PAGEVIEWS => FilamentUmami::pageViews($filter),
            UmamiStatsWidgets::WIDGET_VISITORS => FilamentUmami::visitors($filter),
            UmamiStatsWidgets::WIDGET_VISITS => FilamentUmami::visits($filter),
            UmamiStatsWidgets::WIDGET_BOUNCES => FilamentUmami::bounces($filter),
            UmamiStatsWidgets::WIDGET_TOTAL_TIME => FilamentUmami::totalTime($filter),
        };

        $this->id = $widget->value;
        $label = trans("filament-umami-widgets::translations.widget.$this->id.label");
        $description = $this->getDescription();
        if ($description) {
            $description .= trans_choice('filament-umami-widgets::translations.widget.global.time_range_days', $filter->getDayDiff(), ['value' => $filter->getDayDiff()]);
        }

        return Stat::make($label, $value)
            ->description($description);
    }
}
