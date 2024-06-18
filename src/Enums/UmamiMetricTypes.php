<?php

namespace Schmeits\FilamentUmami\Enums;

enum UmamiMetricTypes: string
{
    case METRIC_URL = 'url';
    case METRIC_REFERRER = 'referrer';
    case METRIC_BROWSER = 'browser';
    case METRIC_OS = 'os';
    case METRIC_DEVICE = 'device';
    case METRIC_COUNTRY = 'country';
    case METRIC_EVENT = 'event';
}
