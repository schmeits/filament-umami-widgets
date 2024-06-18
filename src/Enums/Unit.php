<?php

namespace Schmeits\FilamentUmami\Enums;

enum Unit: string
{
    case UNIT_MINUTE = 'minute';
    case UNIT_HOUR = 'hour';
    case UNIT_DAY = 'day';
    case UNIT_MONTH = 'month';
    case UNIT_YEAR = 'year';
}
