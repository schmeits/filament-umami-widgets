<?php

namespace Schmeits\FilamentUmami\Enums;

enum UmamiStatsWidgets: string implements UmamiWidget
{
    case WIDGET_LIVE = 'live_visitors';
    case WIDGET_PAGEVIEWS = 'pageviews';
    case WIDGET_VISITORS = 'visitors';
    case WIDGET_VISITS = 'visits';
    case WIDGET_BOUNCES = 'bounces';
    case WIDGET_TOTAL_TIME = 'total_time';
}
