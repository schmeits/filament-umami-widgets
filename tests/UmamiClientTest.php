<?php

/**
 * Smoke tests for the HTTP client and the mapping to widget values.
 *
 * WHY: the client builds up the Umami API calls and reads the response. If
 * Laravel changes something in the Http facade or in Carbon, this breaks without
 * anything changing in the widgets. All calls are faked: these tests must never
 * reach a real Umami server.
 */

use Illuminate\Support\Facades\Http;
use Schmeits\FilamentUmami\Concerns\Filter;
use Schmeits\FilamentUmami\Concerns\UmamiClient;
use Schmeits\FilamentUmami\Enums\UmamiMetricTypes;
use Schmeits\FilamentUmami\FilamentUmami;

/**
 * Sets the config to the cloud variant, since that only needs an api key.
 */
function umamiCloudConfig(): void
{
    config()->set('filament-umami-widgets.type', 'cloud');
    config()->set('filament-umami-widgets.cloud_api_key', 'test-key');
    config()->set('filament-umami-widgets.website_id', 'test-site');
    config()->set('filament-umami-widgets.api_endpoint_url', 'https://api.umami.is/v1');
}

it('refuses to start without a website id', function () {
    // The client deliberately throws an exception instead of silently doing
    // nothing. That is the only way a user notices a broken config.
    umamiCloudConfig();
    config()->set('filament-umami-widgets.website_id', null);

    new UmamiClient;
})->throws(Exception::class, 'Umami website ID is required.');

it('refuses to start without an api key in cloud mode', function () {
    umamiCloudConfig();
    config()->set('filament-umami-widgets.cloud_api_key', null);

    new UmamiClient;
})->throws(Exception::class, 'Umami api_key is required.');

it('refuses to start without a username in self-hosted mode', function () {
    config()->set('filament-umami-widgets.type', 'self-hosted');
    config()->set('filament-umami-widgets.username', null);
    config()->set('filament-umami-widgets.password', null);
    config()->set('filament-umami-widgets.website_id', 'test-site');

    new UmamiClient;
})->throws(Exception::class, 'Umami username and password are required.');

it('logs in for the self-hosted variant and uses the token', function () {
    // The self-hosted route does a login call first. If that step disappears,
    // every following call gets a 401 and the widgets stay on zero.
    config()->set('filament-umami-widgets.type', 'self-hosted');
    config()->set('filament-umami-widgets.username', 'admin');
    config()->set('filament-umami-widgets.password', 'secret');
    config()->set('filament-umami-widgets.website_id', 'test-site');
    config()->set('filament-umami-widgets.api_endpoint_url', 'https://umami.test/api');

    Http::fake([
        '*/auth/login' => Http::response(['token' => 'token-123']),
        '*' => Http::response([]),
    ]);

    new UmamiClient;

    Http::assertSent(fn ($request): bool => str_ends_with($request->url(), '/auth/login'));
});

it('sends the api key as a header in cloud mode', function () {
    umamiCloudConfig();

    Http::fake(['*' => Http::response(['x' => 3])]);

    (new UmamiClient)->getActive(new Filter);

    Http::assertSent(fn ($request): bool => $request->hasHeader('x-umami-api-key', 'test-key'));
});

it('calls the correct endpoint for the number of active visitors', function () {
    // The website id is in the path. If it shifts, the user gets the numbers of
    // another site or nothing at all.
    umamiCloudConfig();

    Http::fake(['*' => Http::response(['x' => 3])]);

    expect((new UmamiClient)->getActive(new Filter))->toBe(3);

    Http::assertSent(fn ($request): bool => str_contains($request->url(), 'websites/test-site/active'));
});

it('returns zero when the API returns an error', function () {
    // callApi() deliberately swallows an error status. That is a choice: a
    // dashboard should not fall over just because Umami is briefly down.
    umamiCloudConfig();

    Http::fake(['*' => Http::response([], 500)]);

    expect((new UmamiClient)->getActive(new Filter))->toBe(0);
});

it('sends the filter start and end date', function () {
    // The timestamps are in milliseconds. If Carbon ever handles that
    // differently, the dashboard silently requests the wrong period.
    umamiCloudConfig();

    Http::fake(['*' => Http::response([])]);

    $filter = new Filter;

    (new UmamiClient)->getStats($filter);

    Http::assertSent(function ($request) use ($filter): bool {
        return str_contains($request->url(), 'startAt=' . $filter->from->getTimestampMs())
            && str_contains($request->url(), 'endAt=' . $filter->to->getTimestampMs());
    });
});

it('sends the metric type on a metrics call', function () {
    umamiCloudConfig();

    Http::fake(['*' => Http::response([])]);

    (new UmamiClient)->getMetrics(new Filter, UmamiMetricTypes::METRIC_BROWSER);

    Http::assertSent(fn ($request): bool => str_contains($request->url(), 'type=browser'));
});

it('caches a second identical call', function () {
    // Without caching, a dashboard with 26 widgets makes 26 API calls per
    // refresh.
    umamiCloudConfig();

    Http::fake(['*' => Http::response(['visitors' => ['value' => 9]])]);

    $client = new UmamiClient;
    $filter = new Filter;

    $client->getStats($filter);
    $client->getStats($filter);

    Http::assertSentCount(1);
});

it('reads the visitor counts from the stats response', function () {
    // The mapping from the API response to the number in the widget.
    umamiCloudConfig();

    Http::fake(['*' => Http::response([
        'pageviews' => ['value' => 100],
        'visitors' => ['value' => 50],
        'visits' => ['value' => 70],
        'bounces' => ['value' => 5],
        'totaltime' => ['value' => 90],
    ])]);

    $umami = new FilamentUmami;
    $filter = new Filter;

    expect($umami->pageViews($filter))->toBe(100)
        ->and($umami->visitors($filter))->toBe(50)
        ->and($umami->visits($filter))->toBe(70)
        ->and($umami->bounces($filter))->toBe(5);
});

it('formats the total time as minutes and seconds', function () {
    // CarbonInterval formatting. This is the only part where a Carbon upgrade
    // could produce a visibly different value.
    umamiCloudConfig();

    Http::fake(['*' => Http::response(['totaltime' => ['value' => 125]])]);

    expect((new FilamentUmami)->totalTime(new Filter))->toBe('02:05');
});

it('returns zero when a stats key is missing', function () {
    // Umami omits keys when there is no data. The widget should then show zero
    // and not crash on a missing index.
    umamiCloudConfig();

    Http::fake(['*' => Http::response([])]);

    expect((new FilamentUmami)->visitors(new Filter))->toBe(0);
});

it('maps an empty metric value to the unknown text', function () {
    // Umami returns an empty string when it does not know the referrer. The
    // translation fills that in; if it drops out, the cell is empty.
    umamiCloudConfig();

    Http::fake(['*' => Http::response([
        ['x' => '', 'y' => 4],
    ])]);

    $result = (new FilamentUmami)->metricsReferrer(new Filter);

    expect($result[0]['metric'])
        ->toBe(trans('filament-umami-widgets::translations.widget.metrics_referrer.empty_metric'))
        ->and($result[0]['count'])->toBe(4);
});

it('splits a query string into separate keys', function () {
    umamiCloudConfig();

    Http::fake(['*' => Http::response([
        ['x' => 'search=test&page=2', 'y' => 4],
    ])]);

    expect((new FilamentUmami)->metricsQuery(new Filter)[0]['metric'])
        ->toBe(['search' => 'test', 'page' => '2']);
});
