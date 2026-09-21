<?php

/**
 * Smoke tests for the plugin class.
 *
 * WHY: FilamentUmamiPlugin is the only thing a user adds to their PanelProvider.
 * It implements Filament's Plugin contract. If that contract changes in a new
 * Filament version, this package is unusable immediately, even though all
 * widgets still compile fine.
 */

use Filament\Contracts\Plugin;
use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;

it('implements the Filament Plugin contract', function () {
    expect(FilamentUmamiPlugin::make())->toBeInstanceOf(Plugin::class);
});

it('keeps the plugin id stable', function () {
    // The id is the key filament() uses to find the plugin back. If it changes,
    // every widget throws an exception via FilamentUmamiPlugin::get().
    expect(FilamentUmamiPlugin::make()->getId())->toBe('filament-umami-widgets');
});

it('finds the plugin back in the registered panel', function () {
    // This is what every widget does for the polling interval.
    expect(FilamentUmamiPlugin::get())->toBeInstanceOf(FilamentUmamiPlugin::class);
});

it('uses sixty seconds as the default polling interval', function () {
    expect(FilamentUmamiPlugin::get()->getPollingInterval())->toBe('60s');
});

it('adopts a custom polling interval', function () {
    expect(FilamentUmamiPlugin::make()->pollingInterval('15s')->getPollingInterval())
        ->toBe('15s');
});

it('evaluates a closure as the polling interval', function () {
    // The setter accepts a Closure. That runs through Filament's
    // EvaluatesClosures, so this also covers that trait.
    expect(FilamentUmamiPlugin::make()->pollingInterval(fn (): string => '30s')->getPollingInterval())
        ->toBe('30s');
});

it('falls back to sixty seconds for an empty closure', function () {
    expect(FilamentUmamiPlugin::make()->pollingInterval(null)->getPollingInterval())
        ->toBe('60s');
});

it('provides four default widgets for the grouped stats widget', function () {
    // This list determines what a user sees with no configuration.
    expect(FilamentUmamiPlugin::make()->getDefaultWidgets())->toBe([
        UmamiStatsWidgets::WIDGET_LIVE,
        UmamiStatsWidgets::WIDGET_PAGEVIEWS,
        UmamiStatsWidgets::WIDGET_VISITORS,
        UmamiStatsWidgets::WIDGET_TOTAL_TIME,
    ]);
});

it('adopts a custom widget selection', function () {
    $plugin = FilamentUmamiPlugin::make()
        ->widgetsForGroupedStats([UmamiStatsWidgets::WIDGET_BOUNCES]);

    expect($plugin->getWidgets())->toBe([UmamiStatsWidgets::WIDGET_BOUNCES]);
});

it('falls back to the default widgets when nothing is configured', function () {
    expect(FilamentUmamiPlugin::make()->getWidgets())
        ->toBe(FilamentUmamiPlugin::make()->getDefaultWidgets());
});
