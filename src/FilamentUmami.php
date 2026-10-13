<?php

declare(strict_types=1);

/**
 * src/FilamentUmami.php
 *
 * Turns raw Umami API responses into the values and rows the widgets show.
 * Reached through the FilamentUmami facade.
 */

namespace Schmeits\FilamentUmami;

use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Schmeits\FilamentUmami\Concerns\Filter;
use Schmeits\FilamentUmami\Concerns\UmamiClient;
use Schmeits\FilamentUmami\Enums\UmamiMetricTypes;
use Throwable;

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
        return $this->statValue($filter, 'pageviews');
    }

    public function visitors(Filter $filter): int
    {
        return $this->statValue($filter, 'visitors');
    }

    public function visits(Filter $filter): int
    {
        return $this->statValue($filter, 'visits');
    }

    public function bounces(Filter $filter): int
    {
        return $this->statValue($filter, 'bounces');
    }

    public function totalTime(Filter $filter): string
    {
        return CarbonInterval::seconds($this->statValue($filter, 'totaltime'))->cascade()->format('%I:%S');
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsPages(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_PAGES);
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsTitle(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_TITLE);
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsReferrer(Filter $filter): array
    {
        return $this->transformUmamiMetricResult(
            $this->client->getMetrics($filter, UmamiMetricTypes::METRIC_REFERRER),
            self::translation('widget.metrics_referrer.empty_metric')
        );
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsBrowser(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_BROWSER);
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsOs(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_OS);
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsDevice(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_DEVICE);
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsCountry(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_COUNTRY);
    }

    /**
     * @return list<array{metric: string, country: string, count: int}>
     */
    public function metricsRegion(Filter $filter): array
    {
        return $this->metricsWithCountry($filter, UmamiMetricTypes::METRIC_REGION, 'widget.metrics_region.empty_metric');
    }

    /**
     * @return list<array{metric: string, country: string, count: int}>
     */
    public function metricsCity(Filter $filter): array
    {
        return $this->metricsWithCountry($filter, UmamiMetricTypes::METRIC_CITY, 'widget.metrics_city.empty_metric');
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsLanguage(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_LANGUAGE);
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsScreen(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_SCREEN);
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metricsEvent(Filter $filter): array
    {
        return $this->metrics($filter, UmamiMetricTypes::METRIC_EVENT);
    }

    /**
     * The query string is split into its parameters, so the table can show
     * them as separate badges.
     *
     * @return list<array{metric: string|array<array-key, mixed>, count: int}>
     */
    public function metricsQuery(Filter $filter): array
    {
        $rows = [];

        foreach (self::rows($this->client->getMetrics($filter, UmamiMetricTypes::METRIC_QUERY)) as $item) {
            $metric = self::text($item['x'] ?? null);

            if ($metric !== '') {
                parse_str($metric, $parameters);
                $metric = $parameters;
            }

            $rows[] = [
                'metric' => $metric,
                'count' => self::number($item['y'] ?? null),
            ];
        }

        return $rows;
    }

    /**
     * @return list<array{metric: string, count: int}>
     */
    public function metrics(Filter $filter, UmamiMetricTypes $type): array
    {
        return $this->transformUmamiMetricResult(
            $this->client->getMetrics($filter, $type)
        );
    }

    /**
     * Page views and sessions per day for the last seven days. Days without data
     * are filled with zero.
     *
     * @return array{pageviews: array<string, int>, sessions: array<string, int>}
     */
    public function pageViewsAndSessions(): array
    {
        $result = $this->client->getPageViewsAndSessions();

        return [
            'pageviews' => $this->perDay($result['pageviews'] ?? []),
            'sessions' => $this->perDay($result['sessions'] ?? []),
        ];
    }

    /**
     * Events per day, grouped by event name. Every day in the filter period is
     * present, also without events.
     *
     * @return array<string, list<array{type: string, count: int}>>
     */
    public function websiteEvents(Filter $filter): array
    {
        $days = array_fill_keys(self::dayKeys($filter->from, $filter->to), []);

        foreach (self::rows($this->client->getWebsiteEvents($filter)) as $item) {
            $day = self::day($item['t'] ?? null);

            if ($day === null) {
                continue;
            }

            $days[$day][] = [
                'type' => self::text($item['x'] ?? null),
                'count' => self::number($item['y'] ?? null),
            ];
        }

        return $days;
    }

    /**
     * @param  array<array-key, mixed>  $metrics
     * @return list<array{metric: string, count: int}>
     */
    protected function transformUmamiMetricResult(array $metrics, string $defaultEmptyValue = ''): array
    {
        $rows = [];

        foreach (self::rows($metrics) as $item) {
            $metric = self::text($item['x'] ?? null);

            $rows[] = [
                'metric' => $metric !== '' ? $metric : $defaultEmptyValue,
                'count' => self::number($item['y'] ?? null),
            ];
        }

        return $rows;
    }

    /**
     * @return list<array{metric: string, country: string, count: int}>
     */
    protected function metricsWithCountry(Filter $filter, UmamiMetricTypes $type, string $emptyKey): array
    {
        $empty = self::translation($emptyKey);
        $rows = [];

        foreach (self::rows($this->client->getMetrics($filter, $type)) as $item) {
            $metric = self::text($item['x'] ?? null);

            $rows[] = [
                'metric' => $metric !== '' ? $metric : $empty,
                'country' => self::text($item['country'] ?? null),
                'count' => self::number($item['y'] ?? null),
            ];
        }

        return $rows;
    }

    protected function statValue(Filter $filter, string $key): int
    {
        $stats = $this->client->getStats($filter);
        $stat = $stats[$key] ?? null;

        // Older Umami versions wrap the number as {"value": n}, newer ones
        // return the number itself.
        if (is_array($stat)) {
            $stat = $stat['value'] ?? null;
        }

        return self::number($stat);
    }

    /**
     * Umami returns the day as "2024-01-31 00:00:00" or as an ISO date, the
     * widgets use "2024-01-31", so both are brought back to that key.
     *
     * @return array<string, int>
     */
    protected function perDay(mixed $series): array
    {
        $days = array_fill_keys(self::dayKeys(now()->subDays(6), now()), 0);

        foreach (self::rows(is_array($series) ? $series : []) as $item) {
            $day = self::day($item['x'] ?? null);

            if ($day !== null) {
                $days[$day] = self::number($item['y'] ?? null);
            }
        }

        return $days;
    }

    /**
     * Every day from $from up to and including $to, as "Y-m-d".
     *
     * @return list<string>
     */
    private static function dayKeys(CarbonInterface $from, CarbonInterface $to): array
    {
        $day = $from->toImmutable()->startOfDay();
        $last = $to->toImmutable()->startOfDay();
        $keys = [];

        while ($day->lessThanOrEqualTo($last)) {
            $keys[] = $day->format('Y-m-d');
            $day = $day->addDay();
        }

        return $keys;
    }

    /**
     * @param  array<array-key, mixed>  $items
     * @return list<array<array-key, mixed>>
     */
    private static function rows(array $items): array
    {
        return array_values(array_filter($items, is_array(...)));
    }

    private static function day(mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (Throwable) {
            return null;
        }
    }

    private static function text(mixed $value): string
    {
        return is_string($value) || is_int($value) || is_float($value) ? (string) $value : '';
    }

    private static function number(mixed $value): int
    {
        return is_numeric($value) ? (int) $value : 0;
    }

    private static function translation(string $key): string
    {
        $key = "filament-umami-widgets::translations.{$key}";

        if (! Lang::has($key)) {
            return '';
        }

        $value = trans($key);

        return is_string($value) ? $value : '';
    }
}
