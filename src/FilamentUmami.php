<?php

namespace Schmeits\FilamentUmami;

use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Support\Facades\Lang;
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
        $this->client = new UmamiClient;
    }

    public function getClient(): UmamiClient
    {
        return $this->client;
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

    public function metricsPages(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_PAGES);
    }

    public function metricsTitle(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_TITLE);
    }

    public function metricsReferrer(Filter $filter): array
    {
        return $this->transformUmamiMetricResult(
            $this->client->getMetrics($filter, UmamiMetricTypes::METRIC_REFERRER),
            trans('filament-umami-widgets::translations.widget.metrics_referrer.empty_metric')
        );
    }

    public function metricsBrowser(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_BROWSER);
    }

    public function metricsOs(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_OS);
    }

    public function metricsDevice(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_DEVICE);
    }

    public function metricsCountry(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_COUNTRY);
    }

    public function metricsRegion(Filter $filter): array
    {
        $metrics = $this->client->getMetrics($filter, UmamiMetricTypes::METRIC_REGION);

        return collect($metrics)
            ->map(
                fn ($item) => [
                    'metric' => $item['x'] ?: (Lang::has('filament-umami-widgets::translations.widget.metrics_region.empty_metric') ? trans('filament-umami-widgets::translations.widget.metrics_region.empty_metric') : ''),
                    'country' => $item['country'] ?: '',
                    'count' => (int) $item['y'],
                ]
            )->toArray();
    }

    public function metricsCity(Filter $filter): array
    {
        $metrics = $this->client->getMetrics($filter, UmamiMetricTypes::METRIC_CITY);

        return collect($metrics)
            ->map(
                fn ($item) => [
                    'metric' => $item['x'] ?: (Lang::has('filament-umami-widgets::translations.widget.metrics_city.empty_metric') ? trans('filament-umami-widgets::translations.widget.metrics_city.empty_metric') : ''),
                    'country' => $item['country'] ?: '',
                    'count' => (int) $item['y'],
                ]
            )->toArray();
    }

    public function metricsLanguage(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_LANGUAGE);
    }

    public function metricsScreen(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_SCREEN);
    }

    public function metricsEvent(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_EVENT);
    }

    public function metricsQuery(Filter $filter): array
    {
        return collect($this->client->getMetrics($filter, UmamiMetricTypes::METRIC_QUERY))
            ->map(function ($item) {
                $metric = $item['x'];
                if (filled($metric)) {
                    parse_str($metric, $items);
                    $metric = $items;
                }

                return [
                    'metric' => $metric,
                    'count' => (int) $item['y'],
                ];
            })
            ->toArray();
    }

    public function metrics(Filter $filter, UmamiMetricTypes $type): array
    {
        return $this->transformUmamiMetricResult(
            $this->client->getMetrics($filter, $type)
        );
    }

    public function pageViewsAndSessions(): array
    {
        $result = $this->client->getPageViewsAndSessions();

        return [
            'pageviews' => collect(CarbonPeriod::create(now()->subDays(6), now())->toArray())
                ->mapWithKeys(fn ($val) => [$val->format('Y-m-d') => 0])
                ->merge(collect($result['pageviews'])->pluck('y', 'x')->toArray())->toArray(),
            'sessions' => collect(CarbonPeriod::create(now()->subDays(6), now())->toArray())
                ->mapWithKeys(fn ($val) => [$val->format('Y-m-d') => 0])
                ->merge(collect($result['pageviews'])->pluck('y', 'x')->toArray())->toArray(),
        ];
    }

    public function websiteEvents(Filter $filter): array
    {
        $events = collect($this->client->getWebsiteEvents($filter))
            ->mapToGroups(function ($item) {
                return [
                    $item['t'] => [
                        'type' => $item['x'],
                        'count' => (int) $item['y'],
                    ],
                ];
            });

        return collect(CarbonPeriod::create($filter->from, $filter->to)->toArray())
            ->mapWithKeys(fn ($val) => [$val->format('Y-m-d') => []])
            ->merge($events)->toArray();
    }

    protected function transformUmamiMetricResult(array $metrics, string $defaultEmptyValue = ''): array
    {
        return collect($metrics)
            ->map(fn ($item) => [
                'metric' => $item['x'] ?: $defaultEmptyValue,
                'count' => (int) $item['y'],
            ])
            ->toArray();
    }
}
