<?php

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Schmeits\FilamentUmami\Concerns\Filter;
use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;
use Schmeits\FilamentUmami\Facades\FilamentUmami;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;

abstract class UmamiBaseStatsWidget extends BaseWidget
{
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
        $description = trans("filament-umami-widgets::translations.widget.{$widget->value}.description") .
            trans_choice('filament-umami-widgets::translations.widget.global.time_range_days', $filter->getDayDiff(), ['value' => $filter->getDayDiff()]);

        return Stat::make($label, $value)
            ->description($description);
    }

    public function getFilter()
    {
        $filter = $this->filters['date_range'] ?? null;

        if ($filter === null) {
            $filter = now()->subDays(30)->format('Y-m-d') . ' - ' . now()->endOfDay()->format('Y-m-d');
        }

        $dateParts = explode(' - ', Str::remove('', $filter));
        $startDate = ! is_null($dateParts[0] ?? null) ?
            Carbon::parse($dateParts[0])->startOfDay() :
            now()->subDays(30)->startOfDay();

        $endDate = ! is_null($dateParts[1] ?? null) ?
            Carbon::parse($dateParts[1])->endOfDay() :
            now()->endOfDay();

        return (new Filter())
            ->setFrom($startDate)
            ->setTo($endDate);
    }
}
