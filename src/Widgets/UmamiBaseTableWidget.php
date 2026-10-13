<?php

declare(strict_types=1);

/**
 * src/Widgets/UmamiBaseTableWidget.php
 *
 * Base class for the Umami table widgets.
 */

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Lang;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;
use Schmeits\FilamentUmami\Traits\GetFilterForWidget;
use Schmeits\FilamentUmami\Traits\HasDescription;

abstract class UmamiBaseTableWidget extends Widget
{
    use GetFilterForWidget;
    use HasDescription;
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -9;

    protected ?string $heading = '';

    protected string $id = '';

    protected bool $limitResults = false;

    /**
     * @var view-string
     */
    protected string $view = 'filament-umami-widgets::table-widget';

    public ?string $option = null;

    public function mount(): void
    {
        $this->heading = trans("filament-umami-widgets::translations.widget.$this->id.heading");
        $this->option = $this->validOption();

        if ($this->limitResults) {
            $this->limit = 5;
        }
    }

    /**
     * The option is a public Livewire property, so the browser can send any
     * value. Anything that is not one of the options falls back to the first.
     */
    public function updatedOption(): void
    {
        $this->option = $this->validOption();
    }

    protected function validOption(): ?string
    {
        $keys = array_map(strval(...), array_keys($this->getOptions()));

        return in_array($this->option, $keys, true) ? $this->option : ($keys[0] ?? null);
    }

    public function getPollingInterval(): ?string
    {
        return FilamentUmamiPlugin::current()?->getPollingInterval() ?? '60s';
    }

    public function getTableHeading(): string | Htmlable | null
    {
        return $this->heading;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getHeaders(): array
    {
        return [
            $this->getDefaultMetricHeaderItem(),
            $this->getDefaultCountHeaderItem(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultMetricHeaderItem(string $width = '90%'): array
    {
        return [
            'name' => 'metric',
            'label' => trans("filament-umami-widgets::translations.widget.$this->id.headers.metric"),
            'width' => $width,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultCountHeaderItem(string $width = '10%'): array
    {
        return [
            'name' => 'count',
            'label' => (Lang::has("filament-umami-widgets::translations.widget.$this->id.headers.count") ? trans("filament-umami-widgets::translations.widget.$this->id.headers.count") : trans('filament-umami-widgets::translations.widget.global.headers.count')),
            'width' => $width,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getData(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [];
    }

    public function hasLimitedResults(): bool
    {
        return $this->limitResults;
    }
}
