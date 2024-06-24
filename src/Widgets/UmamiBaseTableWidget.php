<?php

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Lang;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;
use Schmeits\FilamentUmami\Traits\GetFilterForWidget;

abstract class UmamiBaseTableWidget extends Widget
{
    use GetFilterForWidget;
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -9;

    protected ?string $heading = null;

    protected string $id = '';

    protected bool $limitResults = false;

    protected static string $view = 'filament-umami-widgets::table-widget';

    public ?string $option = null;

    public function mount(): void
    {
        $this->heading = trans("filament-umami-widgets::translations.widget.$this->id.heading");
        $this->option = collect($this->getOptions())->keys()->first();
        if ($this->limitResults) {
            $this->limit = 5;
        }
    }

    public function getPollingInterval(): ?string
    {
        return FilamentUmamiPlugin::get()->getPollingInterval();
    }

    public function getTableHeading(): string | Htmlable | null
    {
        return $this->heading;
    }

    public function getHeaders(): array
    {
        return [
            $this->getDefaultMetricHeaderItem(),
            $this->getDefaultCountHeaderItem(),
        ];
    }

    protected function getDefaultMetricHeaderItem(string $width = '90%'): array
    {
        return [
            'name' => 'metric',
            'label' => trans("filament-umami-widgets::translations.widget.$this->id.headers.metric"),
            'width' => $width,
        ];
    }

    protected function getDefaultCountHeaderItem(string $width = '10%'): array
    {
        return [
            'name' => 'count',
            'label' => (Lang::has("filament-umami-widgets::translations.widget.$this->id.headers.count") ? trans("filament-umami-widgets::translations.widget.$this->id.headers.count") : trans('filament-umami-widgets::translations.widget.global.headers.count')),
            'width' => $width,
        ];
    }

    public function getDescription(): ?string
    {
        return (Lang::has("filament-umami-widgets::translations.widget.{$this->id}.description_prefix") ? trans("filament-umami-widgets::translations.widget.{$this->id}.description_prefix") : trans('filament-umami-widgets::translations.widget.global.description_prefix')) .
            trans("filament-umami-widgets::translations.widget.{$this->id}.description") .
            (Lang::has("filament-umami-widgets::translations.widget.{$this->id}.description_postfix") ? trans("filament-umami-widgets::translations.widget.{$this->id}.description_postfix") : trans('filament-umami-widgets::translations.widget.global.description_postfix'));
    }

    public function getData(): array
    {
        return [];
    }

    public function getOptions(): array
    {
        return [];
    }

    public function hasLimitedResults(): bool
    {
        return $this->limitResults;
    }
}
