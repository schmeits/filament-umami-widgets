<?php

namespace Schmeits\FilamentUmami;

use Livewire\Features\SupportTesting\Testable;
use Schmeits\FilamentUmami\Testing\TestsFilamentUmami;
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
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('schmeits/filament-umami-widgets');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        WidgetManager::make()->boot();

        // Testing
        Testable::mixin(new TestsFilamentUmami);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'schmeits/filament-umami-widgets';
    }
}
