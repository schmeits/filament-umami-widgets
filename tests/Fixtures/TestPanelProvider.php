<?php

namespace Schmeits\FilamentUmami\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;

/**
 * Minimal Filament panel with the plugin registered.
 *
 * WHY: every widget in this package calls FilamentUmamiPlugin::get() for the
 * polling interval. That helper does filament('filament-umami-widgets') and so
 * only works inside a panel where the plugin is registered. Without this
 * fixture you cannot render a single widget in a test.
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
