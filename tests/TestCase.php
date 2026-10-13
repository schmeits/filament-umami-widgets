<?php

declare(strict_types=1);

/**
 * tests/TestCase.php
 *
 * Base test case: boots Filament, Livewire and the package.
 */

namespace Schmeits\FilamentUmami\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Livewire\LivewireServiceProvider;
use Malzariey\FilamentDaterangepickerFilter\FilamentDaterangepickerFilterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Schmeits\FilamentUmami\FilamentUmamiServiceProvider;
use Schmeits\FilamentUmami\Tests\Fixtures\TestPanelProvider;

class TestCase extends Orchestra
{
    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        // NOTE: the order here is functional, not cosmetic.
        // Filament\Support rebinds Livewire's DataStore to its own
        // DataStoreOverride with a plain bind(). If Livewire registers earlier,
        // that bind overwrites the singleton instance and every resolve returns a
        // fresh DataStore, so Livewire components no longer hold their state. In a
        // real app, filament/support sorts before livewire/livewire in discovery,
        // so it works there. Here we replicate that order explicitly.
        return [
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            LivewireServiceProvider::class,
            FilamentDaterangepickerFilterServiceProvider::class,
            FilamentUmamiServiceProvider::class,
            // Panel with the plugin registered, so widgets can be rendered.
            TestPanelProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        // Rendering Filament widgets encrypts the Livewire checksum, so without
        // an app key every render throws a MissingAppKeyException. Fixed value,
        // since nothing real is being secured here.
        config()->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));

        // UmamiClient caches every API response. With an on-disk cache those
        // responses leak between tests and between runs, so a test could pass on
        // data from a previous test. In memory it is.
        config()->set('cache.default', 'array');
    }
}
