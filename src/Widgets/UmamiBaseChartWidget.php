<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiBaseChartWidget.php
 *
 * Base class for the Umami chart widgets.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;
use Schmeits\FilamentUmami\Traits\GetFilterForWidget;
use Schmeits\FilamentUmami\Traits\HasDescription;

abstract class UmamiBaseChartWidget extends ChartWidget
{
    use GetFilterForWidget;
    use HasDescription;
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -9;

    protected string $id = '';

    public function getHeading(): string | Htmlable | null
    {
        return trans("filament-umami-widgets::translations.widget.$this->id.heading");
    }

    public function getPollingInterval(): ?string
    {
        return FilamentUmamiPlugin::current()?->getPollingInterval() ?? '60s';
    }

    /**
     * Turns "Y-m-d" keys into the translated date format of the axis labels.
     *
     * @param  array<array-key, mixed>  $days
     * @return list<string>
     */
    protected function dayLabels(array $days): array
    {
        $format = trans('filament-umami-widgets::translations.widget.global.date_format');
        $format = is_string($format) ? $format : 'd-m';
        $labels = [];

        foreach (array_keys($days) as $day) {
            $date = Carbon::make((string) $day);
            $labels[] = $date !== null ? $date->format($format) : (string) $day;
        }

        return $labels;
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            plugins: {
                legend : {
                    display: false,
                },
            },
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1,
                    },
                },
            },
        }
    JS);
    }
}
