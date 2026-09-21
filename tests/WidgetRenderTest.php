<?php

/**
 * Smoke tests that actually render every widget.
 *
 * WHY: the widgets are thin shells around Filament's StatsOverviewWidget,
 * ChartWidget and Widget, plus a custom blade view that uses Filament components
 * (x-filament-widgets::widget, x-filament::badge). That exact coupling breaks on
 * a Filament upgrade, and nothing in the PHP code changes with it. Only
 * rendering exposes that.
 *
 * The HTTP layer is faked: these tests must never reach a real Umami server, and
 * this is about rendering, not about the API.
 */

use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphPageViews;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsGrouped;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsVisitors;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetTableBrowser;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetTableQuery;

// umamiWidgetClasses() lives in tests/Pest.php.

beforeEach(function () {
    // Cloud mode, since it only needs an api key and no login call.
    config()->set('filament-umami-widgets.type', 'cloud');
    config()->set('filament-umami-widgets.cloud_api_key', 'test-key');
    config()->set('filament-umami-widgets.website_id', 'test-site');

    // A response that reads both as a metrics list and as a stats object, so a
    // single fake works for all widget types.
    Http::fake([
        '*/stats*' => Http::response([
            'pageviews' => ['value' => 100],
            'visitors' => ['value' => 50],
            'visits' => ['value' => 70],
            'bounces' => ['value' => 5],
            'totaltime' => ['value' => 3600],
        ]),
        '*/pageviews*' => Http::response([
            'pageviews' => [['x' => now()->format('Y-m-d'), 'y' => 10]],
            'sessions' => [['x' => now()->format('Y-m-d'), 'y' => 4]],
        ]),
        '*/events*' => Http::response([
            ['x' => 'click', 'y' => 3, 't' => now()->format('Y-m-d')],
        ]),
        '*/active*' => Http::response(['x' => 7]),
        // The last line catches metrics. The rows are chosen so they hit all
        // three paths in the view: a normal value, an empty value (falls back to
        // the translation) and a query string (which the query widget breaks up
        // into separate badges).
        '*' => Http::response([
            ['x' => 'Chrome', 'y' => 12, 'country' => 'NL'],
            ['x' => '', 'y' => 3, 'country' => 'NL'],
            ['x' => 'search=test', 'y' => 4, 'country' => 'NL'],
        ]),
    ]);
});

it('renders without errors', function (string $widget) {
    // A separate test case per widget: if one fails, you know immediately which.
    Livewire::test($widget)->assertOk();
})->with(umamiWidgetClasses());

it('shows the heading of a table widget', function () {
    // mount() fills the heading from the translations. If that key disappears,
    // the widget shows an empty heading without anything failing.
    Livewire::test(UmamiWidgetTableBrowser::class)
        ->assertSee(trans('filament-umami-widgets::translations.widget.metrics_browser.heading'));
});

it('shows the data from the API in a table widget', function () {
    // Proves that getData() makes it through the view and is not silently empty.
    Livewire::test(UmamiWidgetTableBrowser::class)
        ->assertSee('Chrome');
});

it('shows the number from the API in a stats widget', function () {
    Livewire::test(UmamiWidgetStatsVisitors::class)
        ->assertSee('50');
});

it('renders the grouped stats widget with the default widget selection', function () {
    // This widget reads FilamentUmamiPlugin::get()->getWidgets(). Without a panel
    // or without the plugin it fails, so this covers that wiring.
    Livewire::test(UmamiWidgetStatsGrouped::class)->assertOk();
});

it('renders a chart widget', function () {
    // ChartWidget has its own lifecycle (updateChartData). That is not in the
    // table widgets, so cover it separately.
    Livewire::test(UmamiWidgetGraphPageViews::class)->assertOk();
});

it('renders the query widget that puts array values in the table', function () {
    // The query widget returns an array per row instead of a string. The view
    // has a separate path for that using x-filament::badge. No other widget hits
    // that path.
    Livewire::test(UmamiWidgetTableQuery::class)
        ->assertOk()
        ->assertSee('search')
        ->assertSee('test');
});

it('limits the number of rows when the widget sets that', function () {
    // limitResults sets the limit in mount() to 5. If that drops out, the user
    // suddenly gets 500 rows on screen.
    Livewire::test(UmamiWidgetTableBrowser::class)
        ->assertSet('limit', 5);
});
