<?php

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
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

    protected static ?string $heading = '';

    public function __construct()
    {
        self::$heading = trans("filament-umami-widgets::translations.widget.$this->id.heading");
    }

    public function getPollingInterval(): ?string
    {
        return FilamentUmamiPlugin::get()->getPollingInterval();
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
