<?php

declare(strict_types=1);

/**
 * src/Concerns/Filter.php
 *
 * The period, unit and row limit of one Umami request, plus its cache hash.
 */

namespace Schmeits\FilamentUmami\Concerns;

use Carbon\CarbonInterface;
use Schmeits\FilamentUmami\Enums\Unit;

class Filter
{
    protected string $id = '';

    public CarbonInterface $from;

    public CarbonInterface $to;

    public string $tz;

    protected string $event = '';

    protected Unit $unit = Unit::UNIT_DAY;

    protected int $limit = 500;

    public function __construct()
    {
        $timezone = config('app.timezone');

        $this->tz = is_string($timezone) ? $timezone : 'UTC';
        $this->from = now()->subDays(6)->startOfDay();
        $this->to = now()->endOfDay();
    }

    /**
     * Building up the query params for the api call
     */
    public function toQuery(): string
    {
        return http_build_query([
            'id' => $this->id,
            'from' => $this->from->format('Y-m-d'),
            'to' => $this->to->format('Y-m-d'),
            'tz' => $this->tz,
            'event' => $this->event,
            'unit' => $this->unit->value,
            'limit' => $this->limit,
        ]);
    }

    /**
     * Generation a md5 hash out of the filter object for caching.
     */
    public function hash(): string
    {
        return md5($this->toQuery());
    }

    /**
     * The from date filter
     *
     * @return $this
     */
    public function setFrom(CarbonInterface $from): Filter
    {
        $this->from = $from;

        return $this;
    }

    /**
     * The to date filter
     *
     * @return $this
     */
    public function setTo(CarbonInterface $to): Filter
    {
        $this->to = $to;

        return $this;
    }

    /**
     * The website ID
     *
     * @return $this
     */
    public function setId(string $id): Filter
    {
        $this->id = $id;

        return $this;
    }

    /**
     * The name of an event to filter for.
     *
     * @return $this
     */
    public function setEvent(string $event): Filter
    {
        $this->event = $event;

        return $this;
    }

    /**
     * The unit parameter buckets the data returned. The unit is automatically converted
     * to the next largest applicable time unit if the maximum is exceeded.
     *
     * minute: Up to 60 minutes.
     * hour: Up to 48 hours.
     * day: Up to 12 months.
     * month: No limit.
     * year: No limit.
     *
     * @return $this
     */
    public function setUnit(Unit $unit): Filter
    {
        $this->unit = $unit;

        return $this;
    }

    public function setLimit(int $limit): Filter
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getDayDiff(): int
    {
        return (int) round($this->from->diffInDays($this->to), 0);
    }
}
