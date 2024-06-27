# Umami Analytics Widgets for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/schmeits/filament-umami-widgets.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-umami-widgets)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/schmeits/filament-umami-widgets/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/schmeits/filament-umami-widgets/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/schmeits/filament-umami-widgets/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/schmeits/filament-umami-widgets/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/schmeits/filament-umami-widgets.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-umami-widgets)

These are some Filament widgets for [Umami](https://umami.is/) (Website Analytics)

![example-screenshot.png](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/example-screenshot.png)

## Installation

You can install the package via composer:

```bash
composer require schmeits/filament-umami-widgets
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-umami-widgets-config"
```

Optionally, you can publish the views 

```bash
php artisan vendor:publish --tag="filament-umami-widgets-views"
```
or translations 
```bash
php artisan vendor:publish --tag="filament-umami-widgets-translations"
```

This is the contents of the published config file:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Umami type
    |--------------------------------------------------------------------------
    |
    | Which version of Umami are you using?
    | Option 1: self_hosted (https://umami.is/docs)
    | Option 2: cloud (https://umami.is/docs/cloud)
    |
    */
    'type' => env('UMAMI_TYPE', 'self-hosted'),

    /*
    |--------------------------------------------------------------------------
    | Umami API Endpoint URL
    |--------------------------------------------------------------------------
    |
    | For the self hosted version you should provide the API
    | endpoint e.g. https://your_url.com/api
    |
    | If you are using the Cloud type your values should be something
    | like: https://api.umami.is/v1
    |
    */
    'api_endpoint_url' => env('UMAMI_API_ENDPOINT', 'https://api.umami.is/v1'),

    /*
    |--------------------------------------------------------------------------
    | Umami Website ID
    |--------------------------------------------------------------------------
    |
    | This is the ID of the website stats you want to show on the website
    |
    | In Umami Cloud you can find the ID by going to Websites
    | Click edit and use the Website ID provided
    |
    | In the self-hosted version navigate to Settings and edit the website
    | use the Website ID provided
    |
    */
    'website_id' => env('UMAMI_WEBSITE_ID', null),

    /*
    |--------------------------------------------------------------------------
    | Umami Http options
    |--------------------------------------------------------------------------
    |
    | The timeout options defines the default timeout for the API requests
    | in seconds
    |
    */
    'timeout' => env('UMAMI_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Self Hosted Options
    |--------------------------------------------------------------------------
    |
    | Add a user to your Umami installation
    | https://umami.is/docs/add-a-user
    |
    */
    'username' => env('UMAMI_USERNAME', null),
    'password' => env('UMAMI_PASSWORD', null),

    /*
    |--------------------------------------------------------------------------
    | Cloud Options
    |--------------------------------------------------------------------------
    |
    | Check the website how to obtain an API key
    | https://umami.is/docs/cloud/api-key
    |
    */
    'cloud_api_key' => env('UMAMI_API_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Caching options
    |--------------------------------------------------------------------------
    |
    | You can set the options for the caching here
    | cache_time is the time the values will be cached in seconds
    |
    */
    'cache_time' => 300,
    
];
```

## Usage

Under `Filament/Pages/` create a new file called `Dashboard.php` with following contents:

```php
namespace App\Filament\Pages;

use Schmeits\FilamentUmami\Concerns\HasFilter;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFilter;
    
}
```

#### Remove the default Dashboard from your PanelProvider
```php
->pages([
    //Pages\Dashboard::class,
])
```
Alternatively if you already have a custom Dashboard, add the `HasFilter` trait to your Dashboard file.

### Add the plugin to your PanelProvider
```php
->plugins([
    \Schmeits\FilamentUmami\FilamentUmamiPlugin::make()
])
```

### Add the Widget to your PanelProvider
```php
->widgets([
    Widgets\AccountWidget::class,
    Widgets\FilamentInfoWidget::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsGrouped::class, // <-- add this widget
])
```

There are different predefined widgets available to use in your dashboard

```php
->widgets([
    // this is the grouped stats widget
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsGrouped::class,

    // these are the separate stats widgets
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsLiveVisitors::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsPageViews::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsVisitors::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsVisits::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsBounces::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsTotalTime::class,

    // and some table widgets
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableUrls::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableTitle::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableReferrers::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableCountry::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableRegion::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableCity::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableDevice::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableOs::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableBrowser::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableLanguage::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableScreen::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableEvents::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableQuery::class,

    // grouped table widgets
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableGroupedPages::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableGroupedGeo::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableGroupedClientInfo::class,

    // chart widgets
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphPageViews::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphSessions::class,
    \Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphEvents::class,
])
```

### Stats Widgets

#### Live Visitors
![stats-live-visitors.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/stats-live-visitors.jpg)

#### Page Hits
![stats-pageviews.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/stats-pageviews.jpg)

#### Number of unique visitors
![stats-visitors.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/stats-visitors.jpg)

#### Number of sessions
![stats-visits.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/stats-visits.jpg)

#### Number of visitors who only visit a single page
![stats-bounces.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/stats-bounces.jpg)

#### Time spent on the website (formatted H:i)
![stats-total-time.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/stats-total-time.jpg)

### Metrics Widgets

#### Referrers
![metrics-referrers.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-referrers.jpg)

#### Url's of the visited pages
![metrics-url.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-url.jpg)

#### Titles of the visited pages
![metrics-titles.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-titles.jpg)

#### Browsers used
![metrics-browsers.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-browsers.jpg)

#### Operating systems used
![metrics-os.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-os.jpg)

#### Devices systems used
![metrics-devices.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-devices.jpg)

#### Languages used
![metrics-language.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-language.jpg)

#### Screen resolutions used
![metrics-resolutions.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-resolutions.jpg)

#### Events occurred
![metrics-events.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-events.jpg)

#### Query strings
![metrics-querystring.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-querystring.jpg)

### Grouped Metrics Widgets

#### Geo information (Countries, Regions, Cities)
![metrics-grouped-geo.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-grouped-geo.jpg)

#### Visited Pages (URL, Title)
![metrics-grouped-pages.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-grouped-pages.jpg)

#### Client Info (Browsers, OS, Devices, Screens, Languages)
![metrics-grouped-client-info.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/metrics-grouped-client-info.jpg)

### Chart widgets
#### Pageviews (last 7 days)
![chart-pageviews.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/chart-pageviews.jpg)

#### Sessions (last 7 days)
![chart-sessions.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/chart-sessions.jpg)

#### Events
![chart-events.jpg](https://github.com/schmeits/filament-umami-widgets/raw/main/docs-assets/screenshots/chart-events.jpg)


### Configure the plugin

If you want to configure the polling interval or define which widgets are shown in the UmamiWidgetStatsGrouped you can pass the StatWidgets to the `widgetsForGroupedStats` function:

```php
    \Schmeits\FilamentUmami\FilamentUmamiPlugin::make()
        ->pollingInterval("60s") //Auto polling interval
        ->widgetsForGroupedStats([
            UmamiStatsWidgets::WIDGET_LIVE,
            UmamiStatsWidgets::WIDGET_PAGEVIEWS,
            UmamiStatsWidgets::WIDGET_VISITORS,
            UmamiStatsWidgets::WIDGET_TOTAL_TIME,
            UmamiStatsWidgets::WIDGET_BOUNCES,
            UmamiStatsWidgets::WIDGET_VISITS,
        ]),
```

## Using the raw Analytics functions
You can also use the facade functions for your own widgets.

### Defining the Filter
```php
use Schmeits\FilamentUmami\Concerns\Filter;

$filter = (new Filter())
    ->setFrom(Carbon::now()->subDays(30))
    ->setTo(Carbon::now());
```

### Get different data
```php
use Schmeits\FilamentUmami\Facades\FilamentUmami;

// Gets the number of active users on a website.
$activeVisitors = FilamentUmami::activeVisitors($filter);

// *** STATS ***

// Pages hits
$views = FilamentUmami::pageViews($filter);

// Number of unique visitors
$visitors = FilamentUmami::visitors($filter);

// Number of sessions
$visits = FilamentUmami::visits($filter);

// Number of visitors who only visit a single page
$bounces = FilamentUmami::bounces($filter);

// Time spent on the website (formatted H:i)
$total_time = FilamentUmami::totalTime($filter);

// *** METRICS ***

// Get pages visited url's
$pages = FilamentUmami::metricsPages($filter);
// or
$pages = FilamentUmami::metrics($filter, \Schmeits\FilamentUmami\Enums\UmamiMetricTypes::METRIC_PAGES);

// *** PAGEVIEWS LAST 7 DAYS ***
$views_and_sessions = FilamentUmami::pageViewsAndSessions(); 

```

## Available Metrics

```php
enum UmamiMetricTypes: string
{
    case METRIC_PAGES = 'url'; // Url's of the Pages visited
    case METRIC_TITLE = 'title'; // Titles of the Pages visited
    case METRIC_REFERRER = 'referrer'; // Referrers (where do the visitors come from)
    case METRIC_BROWSER = 'browser'; // Browser (Chrome, Edge, Safari, etc.)
    case METRIC_OS = 'os'; // Operating system (iOS, MacOS, etc.)
    case METRIC_DEVICE = 'device'; // Devices (Laptop, Mobile, etc.)
    case METRIC_COUNTRY = 'country'; // Countries (US, NL, etc.)
    case METRIC_REGION = 'region'; // Regions (NL-LI, US-AZ, NL-NH, etc.)
    case METRIC_CITY = 'city'; // Cities (Amsterdam, Netherlands / Phoenix, United States / etc)
    case METRIC_LANGUAGE = 'language'; // Languages (Dutch, English, etc.)
    case METRIC_SCREEN = 'screen'; // Screen resolutions (1440x900, 1920x1200, etc.)
    case METRIC_EVENT = 'event'; // Events (Clicked link, etc.)
    case METRIC_QUERY = 'query'; // Query parameters (search=test, etc.)
}

```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Tally Schmeits](https://github.com/schmeits)
- Inspired on the [Pirsch Widget by Devlogx](https://github.com/devlogx/filament-pirsch-dashboard-widget)
- Inspired on the laravel package [Laravel Umami](https://github.com/still-code/laravel-umami)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
