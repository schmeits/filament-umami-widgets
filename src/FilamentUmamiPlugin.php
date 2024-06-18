<?php

namespace Schmeits\FilamentUmami;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Widgets\StatsOverviewWidget;
use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;

class FilamentUmamiPlugin implements Plugin
{
    use EvaluatesClosures;

    protected string | Closure | null $pollingInterval = null;

    protected array $widgets = [];

    public function getId(): string
    {
        return 'filament-umami-widgets';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getPollingInterval(): string
    {
        return $this->evaluate($this->pollingInterval) ?? '60s';
    }

    public function pollingInterval(string | Closure | null $interval = '60s'): static
    {
        $this->pollingInterval = $interval;

        return $this;
    }

    /* @var array<StatsOverviewWidget> $widgets */
    public function widgetsForGroupedStats(array $widgets): static
    {
        $this->widgets = $widgets;

        return $this;
    }

    public function getWidgets(): array
    {
        return empty($this->widgets) ? $this->getDefaultWidgets() : $this->widgets;
    }

    public function getDefaultWidgets(): array
    {
        return [
            UmamiStatsWidgets::WIDGET_LIVE,
            UmamiStatsWidgets::WIDGET_PAGEVIEWS,
            UmamiStatsWidgets::WIDGET_VISITORS,
            UmamiStatsWidgets::WIDGET_TOTAL_TIME,
        ];
    }
}
