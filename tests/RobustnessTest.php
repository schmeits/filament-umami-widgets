<?php

declare(strict_types=1);

/**
 * tests/RobustnessTest.php
 *
 * Tests for the failure paths: Umami down or slow, unexpected responses, browser
 * input that does not fit, and apps with a different date class or panel setup.
 *
 * WHY: a dashboard is the first page people open. Every one of these cases used
 * to break the whole widget (or the page), or silently showed wrong numbers.
 */

use Carbon\CarbonImmutable;
use Filament\Facades\Filament;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Schmeits\FilamentUmami\Concerns\Filter;
use Schmeits\FilamentUmami\Concerns\UmamiClient;
use Schmeits\FilamentUmami\FilamentUmami;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;
use Schmeits\FilamentUmami\Traits\GetFilterForWidget;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphEvents;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphSessions;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetTableGroupedGeo;

beforeEach(function () {
    config()->set('filament-umami-widgets.type', 'cloud');
    config()->set('filament-umami-widgets.cloud_api_key', 'test-key');
    config()->set('filament-umami-widgets.website_id', 'test-site');
    config()->set('filament-umami-widgets.api_endpoint_url', 'https://api.umami.is/v1');
});

/**
 * Calls a protected method, for the chart data that Filament reads internally.
 */
function umamiCallProtected(object $object, string $method): mixed
{
    return (fn () => $this->{$method}())->call($object);
}

it('shows sessions in the sessions chart and not the page views', function () {
    // Both series come from the same endpoint. The sessions chart used to read
    // the page views series.
    Http::fake(['*' => Http::response([
        'pageviews' => [['x' => now()->format('Y-m-d') . ' 00:00:00', 'y' => 10]],
        'sessions' => [['x' => now()->format('Y-m-d') . ' 00:00:00', 'y' => 4]],
    ])]);

    $result = (new FilamentUmami)->pageViewsAndSessions();

    expect($result['pageviews'][now()->format('Y-m-d')])->toBe(10)
        ->and($result['sessions'][now()->format('Y-m-d')])->toBe(4);

    $data = umamiCallProtected(new UmamiWidgetGraphSessions, 'getData');

    expect($data['datasets'][0]['data'])->toContain(4)->not->toContain(10);
});

it('brings Umami date keys back to one key per day', function () {
    // Umami returns "Y-m-d H:i:s" or an ISO date. Without normalising, the day
    // showed up twice: once as 0 and once with the real number.
    Http::fake(['*' => Http::response([
        'pageviews' => [['x' => now()->format('Y-m-d') . 'T00:00:00Z', 'y' => 10]],
        'sessions' => [],
    ])]);

    $pageviews = (new FilamentUmami)->pageViewsAndSessions()['pageviews'];

    expect($pageviews)->toHaveCount(7)
        ->and($pageviews[now()->format('Y-m-d')])->toBe(10)
        ->and(array_sum($pageviews))->toBe(10);
});

it('returns empty values when Umami cannot be reached', function () {
    Http::fake(fn () => throw new ConnectionException('timeout'));

    $umami = new FilamentUmami;

    expect($umami->visitors(new Filter))->toBe(0)
        ->and($umami->metricsBrowser(new Filter))->toBe([])
        ->and($umami->activeVisitors(new Filter))->toBe(0);
});

it('does not cache a failed call', function () {
    // Otherwise a short outage shows zeros for the whole cache time.
    Http::fakeSequence()
        ->push([], 500)
        ->push(['visitors' => ['value' => 9]]);

    $umami = new FilamentUmami;
    $filter = new Filter;

    expect($umami->visitors($filter))->toBe(0)
        ->and($umami->visitors($filter))->toBe(9);
});

it('ignores a response that is not JSON', function () {
    Http::fake(['*' => Http::response('<html>maintenance</html>', 200)]);

    expect((new FilamentUmami)->metricsBrowser(new Filter))->toBe([]);
});

it('reads numbers from both old and new Umami stats responses', function () {
    // Older versions wrap the number as {"value": n}, newer ones do not.
    Http::fake(['*' => Http::response(['visitors' => 12, 'pageviews' => ['value' => 30]])]);

    $umami = new FilamentUmami;

    expect($umami->visitors(new Filter))->toBe(12)
        ->and($umami->pageViews(new Filter))->toBe(30);
});

it('keeps the cache apart per website', function () {
    // Two sites on one cache store must not see each other's numbers.
    Http::fake(['*' => Http::response(['visitors' => ['value' => 1]])]);

    $filter = new Filter;

    (new UmamiClient)->getStats($filter);

    config()->set('filament-umami-widgets.website_id', 'other-site');

    (new UmamiClient)->getStats($filter);

    Http::assertSentCount(2);
});

it('keeps working when a self-hosted Umami is down during login', function () {
    config()->set('filament-umami-widgets.type', 'self-hosted');
    config()->set('filament-umami-widgets.username', 'admin');
    config()->set('filament-umami-widgets.password', 'secret');

    Http::fake(fn () => throw new ConnectionException('down'));

    expect((new FilamentUmami)->visitors(new Filter))->toBe(0);
});

it('still reports wrong self-hosted credentials as an error', function () {
    config()->set('filament-umami-widgets.type', 'self-hosted');
    config()->set('filament-umami-widgets.username', 'admin');
    config()->set('filament-umami-widgets.password', 'wrong');

    Http::fake(['*' => Http::response(['error' => 'Unauthorized'], 401)]);

    new UmamiClient;
})->throws(Exception::class, 'Could not authenticate with Umami');

it('rejects an unknown Umami type with a readable message', function () {
    config()->set('filament-umami-widgets.type', 'saas');

    new UmamiClient;
})->throws(Exception::class, 'Umami type must be');

it('works in an app that uses immutable dates', function () {
    // Date::useClass() changes what now() returns. The filter used to require
    // the mutable Carbon class and threw a TypeError.
    Date::useClass(CarbonImmutable::class);

    try {
        $filter = new Filter;

        expect($filter->from)->toBeInstanceOf(CarbonImmutable::class)
            ->and($filter->getDayDiff())->toBe(7);
    } finally {
        Date::useDefault();
    }
});

it('falls back to the default period for a date range that is not a date', function () {
    // The page filters come from the browser.
    $widget = new class
    {
        use GetFilterForWidget;

        /** @var array<string, mixed> */
        public array $pageFilters = ['date_range' => 'not a date - also not'];
    };

    $filter = $widget->getFilter();

    expect($filter->from->format('Y-m-d'))->toBe(now()->subDays(30)->format('Y-m-d'))
        ->and($filter->to->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
});

it('keeps the row limit between 1 and 500', function () {
    // The limit is a public Livewire property, the browser can send anything.
    $widget = new class
    {
        use GetFilterForWidget;
    };

    expect($widget->setLimit(100000)->getLimit())->toBe(500)
        ->and($widget->setLimit(-5)->getLimit())->toBe(1)
        ->and($widget->setLimit(20)->getLimit())->toBe(20);
});

it('falls back to the first option for an option that does not exist', function () {
    Http::fake(['*' => Http::response([])]);

    Livewire::test(UmamiWidgetTableGroupedGeo::class)
        ->assertSet('option', 'country')
        ->set('option', 'not-an-option')
        ->assertSet('option', 'country')
        ->set('option', 'city')
        ->assertSet('option', 'city');
});

it('keeps every event dataset aligned with the days', function () {
    // A day with events, but not of this type, used to drop out of the dataset,
    // so every following bar moved one day.
    $today = now()->format('Y-m-d');
    $yesterday = now()->subDay()->format('Y-m-d');

    Http::fake(['*' => Http::response([
        ['x' => 'signup', 'y' => 2, 't' => $yesterday . ' 00:00:00'],
        ['x' => 'click', 'y' => 5, 't' => $today . ' 00:00:00'],
    ])]);

    $data = umamiCallProtected(new UmamiWidgetGraphEvents, 'getData');

    $labels = $data['labels'];
    $datasets = collect($data['datasets'])->keyBy('label')->all();

    expect($datasets)->toHaveKeys(['signup', 'click'])
        ->and($datasets['signup']['data'])->toHaveCount(count($labels))
        ->and($datasets['click']['data'])->toHaveCount(count($labels))
        ->and(array_sum($datasets['signup']['data']))->toBe(2)
        ->and(array_sum($datasets['click']['data']))->toBe(5)
        ->and(end($datasets['click']['data']))->toBe(5);
});

it('does not use pure red and green for the event colours', function () {
    // The owner is colour blind: the old palette had red and green next to
    // each other. The legend stays on, so the event name is always text.
    $colors = umamiCallProtected(new UmamiWidgetGraphEvents, 'getColors');

    expect($colors)->not->toContain('#FF0000')->not->toContain('#00FF00');
});

it('uses the default polling interval outside a panel with the plugin', function () {
    // A widget on a panel without the plugin used to throw.
    Filament::setCurrentPanel(null);

    expect(FilamentUmamiPlugin::current())->toBeNull()
        ->and((new UmamiWidgetGraphSessions)->getPollingInterval())->toBe('60s');
});

it('labels the selects for screen readers with a class Filament ships', function () {
    // Filament 4 and 5 only ship their own fi-* classes, so a plain sr-only
    // label rendered as visible text.
    Http::fake(['*' => Http::response([])]);

    Livewire::test(UmamiWidgetTableGroupedGeo::class)
        ->assertSeeHtml('class="fi-sr-only"')
        ->assertDontSeeHtml('class="sr-only"')
        ->assertSee(trans('filament-umami-widgets::translations.widget.global.limit_label'))
        ->assertSee(trans('filament-umami-widgets::translations.widget.global.option_label'));
});
