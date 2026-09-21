<?php

/**
 * Smoke tests for the service provider, the config, the views and the translations.
 *
 * WHY: this package is loaded via package discovery in every Filament app. The
 * provider registers a config file, a view namespace and translations, and on
 * boot it registers 26 widgets as Livewire components. If one of those pieces
 * drops out, the app falls over on startup or a dashboard stays empty without
 * any error.
 */

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Schmeits\FilamentUmami\FilamentUmamiServiceProvider;

it('registers the service provider in the application', function () {
    // getLoadedProviders() only contains providers that were registered and
    // booted without errors, so this proves both phases at once.
    expect(app()->getLoadedProviders())
        ->toHaveKey(FilamentUmamiServiceProvider::class);
});

it('is a Laravel service provider', function () {
    expect(new FilamentUmamiServiceProvider(app()))
        ->toBeInstanceOf(ServiceProvider::class);
});

it('keeps the package name and view namespace stable', function () {
    // Both drive the names of config, views and translations. If they change,
    // published files break for existing users.
    expect(FilamentUmamiServiceProvider::$name)->toBe('filament-umami-widgets')
        ->and(FilamentUmamiServiceProvider::$viewNamespace)->toBe('filament-umami-widgets');
});

it('actually ships the config file on disk', function () {
    // The provider only registers the config file if it exists, and looks it up
    // by the package short name. If either of those shifts, the config is
    // silently skipped.
    expect(file_exists(__DIR__ . '/../config/filament-umami-widgets.php'))->toBeTrue();
});

it('merges every config key into the application', function () {
    // The client reads these keys directly. If one is missing after a change to
    // the config file, the client falls back to defaults or throws an exception
    // the moment a dashboard loads.
    foreach ([
        'type',
        'api_endpoint_url',
        'website_id',
        'timeout',
        'username',
        'password',
        'cloud_api_key',
        'cache_time',
    ] as $key) {
        expect(config()->has("filament-umami-widgets.{$key}"))->toBeTrue(
            "Config key {$key} is missing.",
        );
    }
});

it('registers the package view namespace', function () {
    expect(View::getFinder()->getHints())->toHaveKey('filament-umami-widgets');
});

it('finds the table widget view', function () {
    // All sixteen table widgets share this single view. If it disappears, the
    // whole dashboard fails at once.
    expect(View::exists('filament-umami-widgets::table-widget'))->toBeTrue();
});

it('registers the package translations', function () {
    expect(Lang::has('filament-umami-widgets::translations.filter.title'))->toBeTrue()
        ->and(Lang::has('filament-umami-widgets::translations.widget.global.headers.count'))->toBeTrue();
});

/**
 * Flattens a nested translation array into a flat list of dotted keys.
 *
 * @param  array<string, mixed>  $translations
 * @return array<int, string>
 */
function umamiFlatTranslationKeys(array $translations, string $prefix = ''): array
{
    $keys = [];

    foreach ($translations as $key => $value) {
        $path = $prefix === '' ? (string) $key : $prefix . '.' . $key;

        if (is_array($value)) {
            $keys = array_merge($keys, umamiFlatTranslationKeys($value, $path));

            continue;
        }

        $keys[] = $path;
    }

    return $keys;
}

it('has the same translation keys in every language', function () {
    // A missing key in nl shows the raw key on screen there. That is not caught
    // anywhere else, because there is no error.
    $reference = umamiFlatTranslationKeys(require __DIR__ . '/../resources/lang/en/translations.php');

    foreach (glob(__DIR__ . '/../resources/lang/*', GLOB_ONLYDIR) as $language) {
        $keys = umamiFlatTranslationKeys(require $language . '/translations.php');

        expect($keys)->toEqualCanonicalizing(
            $reference,
            'Language ' . basename($language) . ' has different keys than en.',
        );
    }
});

it('runs the test suite on an in-memory sqlite database', function () {
    // Explicit check: these tests must never touch a real database.
    expect(config('database.default'))->toBe('testing')
        ->and(config('database.connections.testing.driver'))->toBe('sqlite')
        ->and(config('database.connections.testing.database'))->toBe(':memory:');
});
