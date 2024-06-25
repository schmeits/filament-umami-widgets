<?php

namespace Schmeits\FilamentUmami\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int activeVisitors(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static int pageViews(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static int visitors(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static int visits(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static int bounces(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static string totalTime(\Schmeits\FilamentUmami\Concerns\Filter $filter)
 * @method static array metricsReferrer(\Schmeits\FilamentUmami\Concerns\Filter $getFilter)
 * @method static array metricsPages(\Schmeits\FilamentUmami\Concerns\Filter $getFilter)
 * @method static array metricsCity(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsTitle(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsRegion(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsOs(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsDevice(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsCountry(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsBrowser(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsLanguage(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsScreen(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsEvent(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array metricsQuery(\Schmeits\FilamentUmami\Concerns\Filter $setLimit)
 * @method static array pageViewsAndSessions()
 * @method static array websiteEvents(\Schmeits\FilamentUmami\Concerns\Filter $getFilter)
 *
 * @see \Schmeits\FilamentUmami\FilamentUmami
 */
class FilamentUmami extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Schmeits\FilamentUmami\FilamentUmami::class;
    }
}
