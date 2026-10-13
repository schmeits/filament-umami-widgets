<?php

declare(strict_types=1);

/**
 * src/Enums/UmamiWebsiteStats.php
 *
 * The Umami website endpoints, used to pick the default request options.
 */

namespace Schmeits\FilamentUmami\Enums;

enum UmamiWebsiteStats: string
{
    case STAT_ACTIVE = 'active';
    case STAT_EVENTS = 'events';
    case STAT_PAGEVIEWS = 'pageviews';
    case STAT_METRICS = 'metrics';
    case STAT_STATS = 'stats';
}
