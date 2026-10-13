<?php

declare(strict_types=1);

/**
 * tests/Pest.php
 *
 * Pest bootstrap and shared test helpers.
 */

use Schmeits\FilamentUmami\Tests\TestCase;
use Schmeits\FilamentUmami\WidgetManager;

uses(TestCase::class)->in(__DIR__);

/**
 * The list of widgets that WidgetManager registers.
 *
 * The property is protected, so we read it via reflection. Deliberately not
 * copied into the tests: that would stop the test from guarding the real list.
 * It lives here because several test files use it as a dataset and Pest.php is
 * always loaded, even when running --filter on a single file.
 *
 * @return array<int, class-string>
 */
function umamiWidgetClasses(): array
{
    return (new ReflectionClass(WidgetManager::class))->getDefaultProperties()['widgets'];
}
