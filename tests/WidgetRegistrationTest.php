<?php

/**
 * Smoke tests for widget registration.
 *
 * WHY: WidgetManager::boot() registers every widget as a Livewire component. If
 * that goes wrong, the user gets no error but an empty dashboard, or an "Unable
 * to find component" the moment they open the page. This is also the part that
 * breaks first on a Livewire or Filament upgrade, because it relies on
 * ComponentRegistry, an internal Livewire class.
 */

use Filament\Widgets\Widget;
use Livewire\Mechanisms\ComponentRegistry;
use Schmeits\FilamentUmami\WidgetManager;

// umamiWidgetClasses() lives in tests/Pest.php, so every test file can use it as
// a dataset even when running --filter on a single file.

it('resolves a WidgetManager from the container', function () {
    expect(WidgetManager::make())->toBeInstanceOf(WidgetManager::class);
});

it('knows twenty-six widgets', function () {
    // Hard count. If a widget is added or removed, that must be a deliberate
    // change and not a side effect of a refactor.
    expect(umamiWidgetClasses())->toHaveCount(26);
});

it('points to a class that exists', function (string $widget) {
    expect(class_exists($widget))->toBeTrue();
})->with(umamiWidgetClasses());

it('is a Filament widget', function (string $widget) {
    // A widget that does not extend Filament\Widgets\Widget cannot go on a
    // dashboard. Otherwise you only find that out in production.
    expect(is_subclass_of($widget, Widget::class))->toBeTrue();
})->with(umamiWidgetClasses());

it('is instantiable without arguments', function (string $widget) {
    // The chart widgets have their own constructor that fetches translations. If
    // something goes wrong there, the dashboard crashes while building.
    expect(new $widget)->toBeInstanceOf(Widget::class);
})->with(umamiWidgetClasses());

it('is registered as a Livewire component', function (string $widget) {
    // The heart of packageBooted(). Via the name ComponentRegistry generates
    // itself, so a changed naming scheme in Livewire shows up right away.
    $name = app(ComponentRegistry::class)->getName($widget);

    expect(app(ComponentRegistry::class)->getClass($name))->toBe($widget);
})->with(umamiWidgetClasses());

it('uses unique Livewire component names', function () {
    // Two widgets with the same name means one is silently overwritten during
    // registration.
    $names = array_map(
        fn (string $widget): string => app(ComponentRegistry::class)->getName($widget),
        umamiWidgetClasses(),
    );

    expect(array_unique($names))->toHaveCount(count($names));
});
