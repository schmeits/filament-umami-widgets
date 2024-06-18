<?php

namespace Schmeits\FilamentUmami\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Schmeits\FilamentUmami\Concerns\Filter;
use Schmeits\FilamentUmami\FilamentUmamiPlugin;

abstract class UmamiBaseTableWidget extends Widget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -9;

    protected static string $view = 'filament-umami-widgets::table-widget';

    protected ?string $heading = null;

    public string $id = '';

    public function mount()
    {
        $this->heading = trans("filament-umami-widgets::translations.widget.$this->id.heading");
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
            [
                'name' => 'metric',
                'label' => trans("filament-umami-widgets::translations.widget.$this->id.headers.metric"),
                'width' => '90%',
            ],
            [
                'name' => 'count',
                'label' => trans("filament-umami-widgets::translations.widget.$this->id.headers.count"),
                'width' => '10%',
            ],
        ];
    }

    public function getDescription(): ?string
    {
        return trans("filament-umami-widgets::translations.widget.$this->id.description");
    }

    public function getData(): array
    {
        return [];
    }

    /**
     * @throws \Throwable
     */
    public function getFilter(): Filter
    {
        $filter = $this->filters['date_range'] ?? null;

        if ($filter === null) {
            $filter = now()->subDays(30)->format('Y-m-d') . ' - ' . now()->endOfDay()->format('Y-m-d');
        }

        $dateParts = explode(' - ', Str::remove('', $filter));
        $startDate = ! is_null($dateParts[0] ?? null) ?
            Carbon::parse($dateParts[0])->startOfDay() :
            now()->subDays(30)->startOfDay();

        $endDate = ! is_null($dateParts[1] ?? null) ?
            Carbon::parse($dateParts[1])->endOfDay() :
            now()->endOfDay();

        return (new Filter())
            ->setFrom($startDate)
            ->setTo($endDate);
    }
}
