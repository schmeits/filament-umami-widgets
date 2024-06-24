<?php

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Lang;
use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;
use Schmeits\FilamentUmami\Facades\FilamentUmami;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;
use Schmeits\FilamentUmami\Traits\GetFilterForWidget;

abstract class UmamiBaseStatsWidget extends BaseWidget
{
    use GetFilterForWidget;
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -9;

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

        $label = trans("filament-umami-widgets::translations.widget.{$widget->value}.label");
        $description =
            (Lang::has("filament-umami-widgets::translations.widget.{$widget->value}.description_prefix") ? trans("filament-umami-widgets::translations.widget.{$widget->value}.description_prefix") : trans('filament-umami-widgets::translations.widget.global.description_prefix')) .
            trans("filament-umami-widgets::translations.widget.{$widget->value}.description") .
            (Lang::has("filament-umami-widgets::translations.widget.{$widget->value}.description_postfix") ? trans("filament-umami-widgets::translations.widget.{$widget->value}.description_postfix") : trans('filament-umami-widgets::translations.widget.global.description_postfix')) .
            trans_choice('filament-umami-widgets::translations.widget.global.time_range_days', $filter->getDayDiff(), ['value' => $filter->getDayDiff()]);

        return Stat::make($label, $value)
            ->description($description);
    }
}
