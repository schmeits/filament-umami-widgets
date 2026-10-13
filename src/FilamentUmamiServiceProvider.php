<?php

declare(strict_types=1);

/**
 * src/FilamentUmamiServiceProvider.php
 *
 * Registers the config, translations and views of the package and the Livewire
 * components of all Umami widgets.
 */

namespace Schmeits\FilamentUmami;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentUmamiServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-umami-widgets';

    public static string $viewNamespace = 'filament-umami-widgets';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews(static::$viewNamespace)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('schmeits/filament-umami-widgets');
            });
    }

    public function packageBooted(): void
    {
        WidgetManager::make()->boot();
    }
}
