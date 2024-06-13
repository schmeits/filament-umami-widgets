<?php

namespace Schmeits\FilamentUmamiWidgets;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Schmeits\FilamentUmamiWidgets\Commands\FilamentUmamiWidgetsCommand;
use Schmeits\FilamentUmamiWidgets\Testing\TestsFilamentUmamiWidgets;

class FilamentUmamiWidgetsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-umami-widgets';

    public static string $viewNamespace = 'filament-umami-widgets';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('schmeits/filament-umami-widgets');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-umami-widgets/{$file->getFilename()}"),
                ], 'filament-umami-widgets-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentUmamiWidgets());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'schmeits/filament-umami-widgets';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-umami-widgets', __DIR__ . '/../resources/dist/components/filament-umami-widgets.js'),
            Css::make('filament-umami-widgets-styles', __DIR__ . '/../resources/dist/filament-umami-widgets.css'),
            Js::make('filament-umami-widgets-scripts', __DIR__ . '/../resources/dist/filament-umami-widgets.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentUmamiWidgetsCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_filament-umami-widgets_table',
        ];
    }
}
