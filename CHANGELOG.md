# Changelog

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


All notable changes to `filament-umami-widgets` will be documented in this file.

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
