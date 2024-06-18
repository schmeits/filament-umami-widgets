<?php

namespace Schmeits\FilamentUmami;

use Carbon\CarbonInterval;
use Exception;
use Schmeits\FilamentUmami\Concerns\Filter;
use Schmeits\FilamentUmami\Concerns\UmamiClient;
use Schmeits\FilamentUmami\Enums\UmamiMetricTypes;

class FilamentUmami
{
    protected UmamiClient $client;

    /**
     * @throws Exception Missing parameters
     */
    public function __construct()
    {
        $this->client = new UmamiClient();
    }

    public function activeVisitors(Filter $filter): int
    {
        return $this->client->getActive($filter);
    }

    public function pageViews(Filter $filter): int
    {
        $stats = $this->client->getStats($filter);

        return $stats['pageviews']['value'] ?? 0;
    }

    public function visitors(Filter $filter): int
    {
        $stats = $this->client->getStats($filter);

        return $stats['visitors']['value'] ?? 0;
    }

    public function visits(Filter $filter): int
    {
        $stats = $this->client->getStats($filter);

        return $stats['visits']['value'] ?? 0;
    }

    public function bounces(Filter $filter): int
    {
        $stats = $this->client->getStats($filter);

        return $stats['bounces']['value'] ?? 0;
    }

    public function totalTime(Filter $filter): string
    {
        $stats = $this->client->getStats($filter);

        return CarbonInterval::seconds($stats['totaltime']['value'] ?? 0)->cascade()->format('%I:%S');
    }

    public function metricsReferrer(Filter $filter): array
    {
        $metrics = $this->client->getMetrics($filter, UmamiMetricTypes::METRIC_REFERRER);

        return collect($metrics)->map(fn ($item) => ['metric' => $item['x'] ?: trans('filament-umami-widgets::translations.widget.metrics_referrer.empty_referrer'), 'count' => (int) $item['y']])->toArray();
    }

    public function metricsUrl(Filter $filter): array
    {
        $metrics = $this->client->getMetrics($filter, UmamiMetricTypes::METRIC_URL);

        return collect($metrics)->map(fn ($item) => ['metric' => $item['x'] ?? '', 'count' => (int) $item['y']])->toArray();
    }
}
