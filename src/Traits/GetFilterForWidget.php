<?php

namespace Schmeits\FilamentUmami\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
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

        return (new Filter)
            ->setFrom($startDate)
            ->setTo($endDate)
            ->setLimit($this->getLimit());
    }
}
