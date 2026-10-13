<?php

declare(strict_types=1);

/**
 * tests/Fixtures/TestPanelProvider.php
 *
 * Panel with the plugin registered, used by the tests.
 */

namespace Schmeits\FilamentUmami\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;

/**
 * Minimal Filament panel with the plugin registered.
 *
 * WHY: the widgets read the polling interval and the grouped stats selection
 * from the plugin on the current panel (FilamentUmamiPlugin::current()). This
 * fixture makes that panel exist, so the tests cover the configured path and
 * not only the defaults.
 */
class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('test')
            ->path('test')
            ->plugin(FilamentUmamiPlugin::make());
    }
}
