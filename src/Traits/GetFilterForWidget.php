<?php

declare(strict_types=1);

/**
 * src/Traits/GetFilterForWidget.php
 *
 * Builds the Umami filter for a widget from the dashboard date range and the
 * row limit the user picked.
 */

namespace Schmeits\FilamentUmami\Traits;

use Illuminate\Support\Carbon;
use Schmeits\FilamentUmami\Concerns\Filter;
use Throwable;

trait GetFilterForWidget
{
    public int $limit = 500;

    /**
     * The limit is a public Livewire property, so the browser can send any
     * number. It is kept between 1 and the Umami maximum of 500.
     */
    public function getLimit(): int
    {
        return min(max($this->limit, 1), 500);
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getFilter(): Filter
    {
        $range = $this->pageFilters['date_range'] ?? null;

        [$start, $end] = array_pad(explode(' - ', is_string($range) ? $range : '', 2), 2, null);

        return (new Filter)
            ->setFrom(self::parseDate($start)?->startOfDay() ?? now()->subDays(30)->startOfDay())
            ->setTo(self::parseDate($end)?->endOfDay() ?? now()->endOfDay())
            ->setLimit($this->getLimit());
    }

    /**
     * The date range comes from the page filters, which the browser controls.
     * A value that is not a date falls back to the default period instead of
     * breaking the widget.
     */
    private static function parseDate(?string $value): ?Carbon
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse(trim($value));
        } catch (Throwable) {
            return null;
        }
    }
}
