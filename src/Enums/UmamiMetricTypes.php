<?php

namespace Schmeits\FilamentUmami\Enums;

enum UmamiMetricTypes: string
{
    case METRIC_PAGES = 'url'; // Url's of the Pages visited
    case METRIC_TITLE = 'title'; // Titles of the Pages visited
    case METRIC_REFERRER = 'referrer'; // Referrers (where do the visitors come from)
    case METRIC_BROWSER = 'browser'; // Browser (Chrome, Edge, Safari, etc.)
    case METRIC_OS = 'os'; // Operating system (iOS, MacOS, etc.)
    case METRIC_DEVICE = 'device'; // Devices (Laptop, Mobile, etc.)
    case METRIC_COUNTRY = 'country'; // Countries (United States, Netherlands, etc.)
    case METRIC_REGION = 'region'; // Regions (Gelderland, Netherlands / Arizona, United States / etc.)
    case METRIC_CITY = 'city'; // Cities (Amsterdam, Netherlands / Phoenix, United States / etc)
    case METRIC_LANGUAGE = 'language'; // Languages (Dutch, English, etc.)
    case METRIC_SCREEN = 'screen'; // Screen resolutions (1440x900, 1920x1200, etc.)
    case METRIC_EVENT = 'event'; // Events (Clicked link, etc.)
    case METRIC_QUERY = 'query'; // Query parameters (search=test, etc.)
}
