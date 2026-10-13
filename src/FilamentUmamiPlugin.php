<?php

declare(strict_types=1);

/**
 * src/FilamentUmamiPlugin.php
 *
 * The Filament plugin: polling interval and the selection for the grouped
 * stats widget.
 */

namespace Schmeits\FilamentUmami;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use LogicException;
use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;

class FilamentUmamiPlugin implements Plugin
{
    use EvaluatesClosures;

    protected string | Closure | null $pollingInterval = null;

    /**
     * @var array<UmamiStatsWidgets>
     */
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
        $plugin = app(static::class);

        if (! $plugin instanceof static) {
            throw new LogicException('The container did not return a ' . static::class . '.');
        }

        return $plugin;
    }

    /**
     * The plugin registered on the current panel. Throws when there is none; use
     * current() where a missing plugin is not an error.
     */
    public static function get(): static
    {
        $plugin = filament(static::make()->getId());

        if (! $plugin instanceof static) {
            throw new LogicException('The Umami plugin is not registered on the current panel.');
        }

        return $plugin;
    }

    /**
     * The plugin as registered on the current panel, or null when the widget is
     * used outside a panel or on a panel without this plugin. Widgets use this
     * instead of get(), so they keep working with the defaults.
     */
    public static function current(): ?static
    {
        $panel = filament()->getCurrentPanel();
        $id = static::make()->getId();

        if ($panel === null || ! $panel->hasPlugin($id)) {
            return null;
        }

        $plugin = $panel->getPlugin($id);

        return $plugin instanceof static ? $plugin : null;
    }

    public function getPollingInterval(): string
    {
        $interval = $this->evaluate($this->pollingInterval);

        return is_string($interval) && $interval !== '' ? $interval : '60s';
    }

    public function pollingInterval(string | Closure | null $interval = '60s'): static
    {
        $this->pollingInterval = $interval;

        return $this;
    }

    /**
     * The numbers the grouped stats widget shows, in this order.
     *
     * @param  array<UmamiStatsWidgets>  $widgets
     */
    public function widgetsForGroupedStats(array $widgets): static
    {
        $this->widgets = $widgets;

        return $this;
    }

    /**
     * @return array<UmamiStatsWidgets>
     */
    public function getWidgets(): array
    {
        return empty($this->widgets) ? $this->getDefaultWidgets() : $this->widgets;
    }

    /**
     * @return list<UmamiStatsWidgets>
     */
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
