<?php

declare(strict_types=1);

/**
 * src/Enums/Unit.php
 *
 * The time units Umami buckets data in.
 */

namespace Schmeits\FilamentUmami\Enums;

enum Unit: string
{
    case UNIT_MINUTE = 'minute';
    case UNIT_HOUR = 'hour';
    case UNIT_DAY = 'day';
    case UNIT_MONTH = 'month';
    case UNIT_YEAR = 'year';
}
