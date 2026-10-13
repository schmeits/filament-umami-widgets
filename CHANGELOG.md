# Changelog

All notable changes to `filament-umami-widgets` will be documented in this file.

## 2.1.0 - 2026-10-13

Robustness, accessibility and maintenance. No changes needed in your code.

### Fixed

- The sessions chart showed page views instead of sessions.
- The events chart dropped a day from a dataset when that day only had other events, so the bars moved one day. Every dataset now has a value for every day.
- Days are normalised to `Y-m-d`, whatever date format Umami returns. A day no longer appears twice.
- A slow or unreachable Umami, a non-JSON response or a missing key no longer breaks a widget. The widget shows empty values and a failed call is not cached, so it recovers as soon as Umami is back.
- A self-hosted Umami that is down during login no longer breaks the dashboard. Wrong credentials still raise a clear error.
- Apps that use immutable dates (`Date::useClass(CarbonImmutable::class)`) no longer get a TypeError.
- A widget on a panel without the plugin uses the default polling interval instead of throwing.
- A date range, row limit or table option sent by the browser that is not valid falls back to the default.
- Cache keys (including the login token) are scoped per Umami server and website, so apps sharing a cache store no longer see each other's data.
- The hidden labels of the table selects used `sr-only`, which Filament 4 and 5 do not ship, so they rendered as visible text. They now use `fi-sr-only` and are translated. If you published the views, publish them again.

### Changed

- Event colours use the colour-blind safe Okabe-Ito palette. The legend stays visible, so the event name is always readable as text.
- Laravel 11 is no longer tested: Composer blocks its releases because of open security advisories. Supported are Laravel 12 and 13.
- New `FilamentUmamiPlugin::current()`, which returns null instead of throwing when the plugin is not on the current panel.

### Maintenance

- `declare(strict_types=1)` in every PHP file, enforced by an architecture test.
- PHPStan runs at level max on `src` and `config` without a baseline.
- 18 new tests for the failure paths above.
- Removed the unused npm toolchain, the empty testing mixin and other skeleton leftovers. The Composer package no longer contains screenshots or development files.
- Dependabot: Composer added, updates grouped, 3 day cooldown.
- The changelog workflow uses the default branch instead of a hard-coded `main`.

## 2.0.0 - 2026-10-13

Support for Filament 4 and 5, and with that Laravel 13.

- Requires Filament 4 or 5, Laravel 11.28 or higher and PHP 8.2 or higher. The Filament 3 version stays available as 1.x on the `3.x` branch.
- `malzariey/filament-daterangepicker-filter` raised to `^4.0.7|^5.0`.
- The date filter uses the Filament 4 schema API (`filtersForm(Schema $schema)`), and widgets read `$pageFilters`.
- The table widget view now uses only Filament's own `fi-ta-*` classes and components, because Filament 4 no longer ships arbitrary Tailwind classes. The widget description moved from below the table to under the heading.
- Widgets are registered the same way Filament does: through ComponentRegistry on Livewire 3, by class name on Livewire 4.
- CI tests PHP 8.2 to 8.5 against Laravel 11, 12 and 13 with Filament 4 and 5. PHPStan runs again (Larastan was missing from require-dev).

## 2026-09-21 - CI fix: add guzzlehttp/guzzle to require-dev

- Added `guzzlehttp/guzzle` to `require-dev` so Laravel's `Http::fake()` / `Http::response()` can resolve `GuzzleHttp\Psr7\Response` on every CI matrix combination. On Laravel 10 (testbench 8) with lower PHP versions Guzzle was not pulled in transitively, which made the `run-tests` workflow fail with "Class GuzzleHttp\Psr7\Response not found".

## 2026-02-04 10:44 - Composer Security Update (Automated)

- Security vulnerabilities opgelost via composer update --with-all-dependencies
- Run composer show --locked voor volledige package lijst
- Composer audit: Geen openstaande kwetsbaarheden meer


## 1.4.4 - 2025-02-26

Bump filament-daterangepicker-filter version to add support for laravel 12 (thanks to @atmonshi)

## 1.4.3 - 2024-09-05

Bug: Ofcourse we have to use the max value getting the timeout :)  (noticed by Lmtc 🙏🏻)
Fixed: Add limitResults to the Grouped Geo Table

## 1.4.2 - 2024-06-27

Typo squashing and adding inspiration

## 1.4.1 - 2024-06-27

Fixed bug in BaseTableWidget

## 1.4.0 - 2024-06-25

New:

- added websiteEvents Widget

Bugfixes:

- chartwidget headers disappearing

Internals:

- deduplication of code for abstract baseWidget classes

## 1.3.2 - 2024-06-24

Fixed small bug in default filter

## 1.3.1 - 2024-06-24

Format date option added to graph widgets

## 1.3.0 - 2024-06-24

Added Chart Widgets

- Pageviews (\Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphPageViews::class)
- Sessions (\Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphSessions::class)

## 1.2.1 - 2024-06-21

Forgot Widget use in WidgetManager

## 1.2.0 - 2024-06-21

Added Widgetmanager to register the Widgets to be used on other pages

## 1.1.0 - 2024-06-21

Added some Table Widgets

- UmamiWidgetTableTitle
- UmamiWidgetTableUrls
- UmamiWidgetTableCountry
- UmamiWidgetTableRegion
- UmamiWidgetTableCity
- UmamiWidgetTableDevice
- UmamiWidgetTableOs
- UmamiWidgetTableBrowser
- UmamiWidgetTableLanguage
- UmamiWidgetTableScreen
- UmamiWidgetTableEvents
- UmamiWidgetTableQuery
- UmamiWidgetTableGroupedPages
- UmamiWidgetTableGroupedGeo
- UmamiWidgetTableGroupedClientInfo

Added limiting to table widgets

## 1.0.1 - 2024-06-18

Dutch translations

## 1.0.0 - 202X-XX-XX

- initial release
