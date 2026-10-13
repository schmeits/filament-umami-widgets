<?php

declare(strict_types=1);

/**
 * src/Facades/FilamentUmami.php
 *
 * Facade for the FilamentUmami service that the widgets read their data from.
 */

namespace Schmeits\FilamentUmami\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int activeVisitors(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static int pageViews(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static int visitors(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static int visits(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static int bounces(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static string totalTime(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsReferrer(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsPages(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, country: string, count: int}> metricsCity(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsTitle(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, country: string, count: int}> metricsRegion(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsOs(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsDevice(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsCountry(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsBrowser(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsLanguage(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsScreen(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metricsEvent(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string|array<array-key, mixed>, count: int}> metricsQuery(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static list<array{metric: string, count: int}> metrics(\Schmeits\FilamentUmami\Concerns\Filter $filter, \Schmeits\FilamentUmami\Enums\UmamiMetricTypes $type)
 * @method static array{pageviews: array<string, int>, sessions: array<string, int>} pageViewsAndSessions()
 * @method static array<string, list<array{type: string, count: int}>> websiteEvents(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 *
 * @see \Schmeits\FilamentUmami\FilamentUmami
 */
class FilamentUmami extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Schmeits\FilamentUmami\FilamentUmami::class;
    }
}
