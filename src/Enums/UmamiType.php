<?php

declare(strict_types=1);

/**
 * src/Enums/UmamiType.php
 *
 * Self-hosted or cloud Umami.
 */

namespace Schmeits\FilamentUmami\Enums;

enum UmamiType: string
{
    case TYPE_SELF_HOSTED = 'self-hosted';
    case TYPE_CLOUD = 'cloud';
}
