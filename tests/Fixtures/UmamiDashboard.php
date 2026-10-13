<?php

declare(strict_types=1);

/**
 * tests/Fixtures/UmamiDashboard.php
 *
 * Dashboard page with the date filter, used by the filter tests.
 */

namespace Schmeits\FilamentUmami\Tests\Fixtures;

use Filament\Pages\Dashboard;
use Schmeits\FilamentUmami\Concerns\HasFilter;

/**
 * A dashboard that uses the HasFilter trait, the way the README tells users to.
 */
class UmamiDashboard extends Dashboard
{
    use HasFilter;

    public function getWidgets(): array
    {
        return [];
    }
}
