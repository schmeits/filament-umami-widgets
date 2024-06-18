# Umami Analytics Widgets for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/schmeits/filament-umami-widgets.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-umami-widgets)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/schmeits/filament-umami-widgets/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/schmeits/filament-umami-widgets/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/schmeits/filament-umami-widgets/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/schmeits/filament-umami-widgets/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/schmeits/filament-umami-widgets.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-umami-widgets)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require schmeits/filament-umami-widgets
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-umami-widgets-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-umami-widgets-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-umami-widgets-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentUmami = new Schmeits\FilamentUmami();
echo $filamentUmami->echoPhrase('Hello, Schmeits!');
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
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
