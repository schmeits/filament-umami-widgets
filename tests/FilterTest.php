<?php

declare(strict_types=1);

/**
 * tests/FilterTest.php
 *
 * Tests for the date filter.
 *
 * WHY: the filter is the one place where this package touches Filament's form
 * API (HasFiltersForm) and the page filters on the widgets. Both changed name
 * and signature between Filament 3 and 4, and a wrong signature only shows up
 * when the dashboard is opened. A widget that reads the wrong property does not
 * fail at all: it silently falls back to the default range.
 */

use Filament\Facades\Filament;
use Livewire\Livewire;
use Schmeits\FilamentUmami\Tests\Fixtures\UmamiDashboard;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsVisitors;

it('renders the date range filter on a dashboard that uses HasFilter', function () {
    Filament::setCurrentPanel(Filament::getPanel('test'));

    Livewire::test(UmamiDashboard::class)
        ->assertOk()
        ->assertSee(trans('filament-umami-widgets::translations.filter.title'))
        ->assertSee(trans('filament-umami-widgets::translations.filter.select_range'));
});

it('uses the range from the page filters', function () {
    $widget = new UmamiWidgetStatsVisitors;
    $widget->pageFilters = ['date_range' => '01-10-2026 - 05-10-2026'];

    $filter = $widget->getFilter();

    expect($filter->from->format('Y-m-d H:i:s'))->toBe('2026-10-01 00:00:00')
        ->and($filter->to->format('Y-m-d H:i:s'))->toBe('2026-10-05 23:59:59');
});

it('falls back to the last thirty days without page filters', function () {
    $filter = (new UmamiWidgetStatsVisitors)->getFilter();

    expect($filter->from->toDateString())->toBe(now()->subDays(30)->toDateString())
        ->and($filter->to->toDateString())->toBe(now()->toDateString());
});
