<?php

declare(strict_types=1);

/**
 * src/Enums/UmamiTableWidgets.php
 *
 * The available table widgets.
 */

namespace Schmeits\FilamentUmami\Enums;

enum UmamiTableWidgets: string implements UmamiWidget
{
    case WIDGET_METRICS_REFERRER = 'metrics_referrer';
}
