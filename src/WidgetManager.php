<?php

declare(strict_types=1);

namespace Schmeits\FilamentUmami;

use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Livewire\Livewire;
use Livewire\Mechanisms\ComponentRegistry;

class WidgetManager
{
    /**
     * @var array<string, class-string>
     */
    protected array $livewireComponents = [];

    protected array $widgets = [
        Widgets\UmamiWidgetStatsBounces::class,
        Widgets\UmamiWidgetStatsGrouped::class,
        Widgets\UmamiWidgetStatsLiveVisitors::class,
        Widgets\UmamiWidgetStatsPageViews::class,
        Widgets\UmamiWidgetStatsTotalTime::class,
        Widgets\UmamiWidgetStatsVisitors::class,
        Widgets\UmamiWidgetStatsVisits::class,
        Widgets\UmamiWidgetTableBrowser::class,
        Widgets\UmamiWidgetTableCity::class,
        Widgets\UmamiWidgetTableCountry::class,
        Widgets\UmamiWidgetTableDevice::class,
        Widgets\UmamiWidgetTableEvents::class,
        Widgets\UmamiWidgetTableGroupedClientInfo::class,
        Widgets\UmamiWidgetTableGroupedGeo::class,
        Widgets\UmamiWidgetTableGroupedPages::class,
        Widgets\UmamiWidgetTableLanguage::class,
        Widgets\UmamiWidgetTableOs::class,
        Widgets\UmamiWidgetTableQuery::class,
        Widgets\UmamiWidgetTableReferrers::class,
        Widgets\UmamiWidgetTableRegion::class,
        Widgets\UmamiWidgetTableScreen::class,
        Widgets\UmamiWidgetTableTitle::class,
        Widgets\UmamiWidgetTableUrls::class,
        Widgets\UmamiWidgetGraphPageViews::class,
        Widgets\UmamiWidgetGraphSessions::class,
    ];

    public static function make(): static
    {
        return app(static::class);
    }

    public function boot(): void
    {
        $this->enqueueWidgetsForRegistration();

        foreach ($this->livewireComponents as $componentName => $componentClass) {
            Livewire::component($componentName, $componentClass);
        }

        $this->livewireComponents = [];
    }

    protected function enqueueWidgetsForRegistration(): void
    {
        foreach ($this->widgets as $widget) {
            $this->queueLivewireComponentForRegistration($this->normalizeWidgetClass($widget));
        }
    }

    /**
     * @param  class-string<Widget> | WidgetConfiguration  $widget
     * @return class-string<Widget>
     */
    public function normalizeWidgetClass(string | WidgetConfiguration $widget): string
    {
        if ($widget instanceof WidgetConfiguration) {
            return $widget->widget;
        }

        return $widget;
    }

    protected function queueLivewireComponentForRegistration(string $component): void
    {
        $componentName = app(ComponentRegistry::class)->getName($component);

        $this->livewireComponents[$componentName] = $component;
    }
}
