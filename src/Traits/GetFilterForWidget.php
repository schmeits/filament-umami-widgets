<?php

namespace Schmeits\FilamentUmami\Traits;

use Illuminate\Support\Carbon;
use Schmeits\FilamentUmami\Concerns\Filter;

trait GetFilterForWidget
{
    public int $limit = 500;

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getFilter()
    {
        $filter = $this->pageFilters['date_range'] ?? null;

        if ($filter === null) {
            $filter = now()->subDays(30)->format('Y-m-d') . ' - ' . now()->endOfDay()->format('Y-m-d');
        }

        [$start, $end] = array_pad(explode(' - ', $filter, 2), 2, null);

        $startDate = filled($start) ? Carbon::parse($start)->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = filled($end) ? Carbon::parse($end)->endOfDay() : now()->endOfDay();

        return (new Filter)
            ->setFrom($startDate)
            ->setTo($endDate)
            ->setLimit($this->getLimit());
    }
}
