<?php

declare(strict_types=1);

/**
 * src/Concerns/UmamiClient.php
 *
 * HTTP client for the Umami API: authenticates (self-hosted login or cloud api
 * key), performs the calls and caches the responses.
 */

namespace Schmeits\FilamentUmami\Concerns;

use Closure;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Schmeits\FilamentUmami\Enums\UmamiMetricTypes;
use Schmeits\FilamentUmami\Enums\UmamiType;
use Schmeits\FilamentUmami\Enums\UmamiWebsiteStats;
use Throwable;

class UmamiClient
{
    private PendingRequest $http;

    private UmamiType $type;

    private string $baseUrl;

    private string $site_id;

    /**
     * Prefix for every cache key, unique per Umami server and website. Without
     * it, two apps or panels that share a cache store would read each other's
     * statistics and login token.
     */
    private string $cachePrefix;

    /**
     * @throws Exception when the configuration is incomplete
     */
    public function __construct()
    {
        $type = UmamiType::tryFrom(self::configString('type', 'self-hosted'));

        if ($type === null) {
            throw new Exception('Umami type must be "self-hosted" or "cloud".');
        }

        $this->type = $type;

        $this->baseUrl = rtrim(self::configString('api_endpoint_url'), '/');

        // The minimum timeout is one second.
        $timeout = max((int) self::configString('timeout', '5'), 1);

        $this->http = Http::baseUrl($this->baseUrl)->timeout($timeout);

        $siteId = self::configString('website_id');

        if ($siteId === '') {
            throw new Exception('Umami website ID is required.');
        }

        $this->site_id = $siteId;
        $this->cachePrefix = 'filament-umami-widgets:' . md5($this->type->value . '|' . $this->baseUrl . '|' . $this->site_id) . ':';

        if ($this->type === UmamiType::TYPE_SELF_HOSTED) {
            $username = self::configString('username');
            $password = self::configString('password');

            if ($username === '' || $password === '') {
                throw new Exception('Umami username and password are required.');
            }

            $token = $this->getToken($username, $password);

            if ($token !== null) {
                $this->http->withToken($token);
            }
        } else {
            $apiKey = self::configString('cloud_api_key');

            if ($apiKey === '') {
                throw new Exception('Umami api_key is required.');
            }

            $this->http->withHeader('x-umami-api-key', $apiKey);
        }
    }

    public function getActive(Filter $filter): int
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_ACTIVE, $filter);

        $value = $this->callWebsiteApi('active', $options);

        return self::toInt($value['x'] ?? ($value['visitors'] ?? 0));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getStats(Filter $filter): array
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_STATS, $filter);

        return $this->getCachedValue('get-stats-' . $filter->hash(), fn (): ?array => $this->requestWebsiteApi('stats', $options));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getPageViewsAndSessions(): array
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_PAGEVIEWS, new Filter);

        return $this->getCachedValue('get-pageviews', fn (): ?array => $this->requestWebsiteApi('pageviews', $options));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getWebsiteEvents(Filter $filter): array
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_EVENTS, $filter);

        return $this->getCachedValue('get-events-' . $filter->hash(), fn (): ?array => $this->requestWebsiteApi('events', $options));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getMetrics(Filter $filter, UmamiMetricTypes $type): array
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_METRICS, $filter);

        $options['type'] = $type->value;

        return $this->getCachedValue('get-metrics-' . $type->value . '-' . $filter->hash(), fn (): ?array => $this->requestWebsiteApi('metrics', $options));
    }

    /**
     * Only successful responses are cached. A failed call returns an empty
     * result for this request, so the dashboard recovers as soon as Umami is
     * reachable again instead of showing zeros for the whole cache time.
     *
     * @param  Closure(): (array<array-key, mixed>|null)  $callback
     * @return array<array-key, mixed>
     */
    private function getCachedValue(string $key, Closure $callback): array
    {
        $key = $this->cachePrefix . $key;

        $cached = Cache::get($key);

        if (is_array($cached)) {
            return $cached;
        }

        $value = $callback();

        if ($value === null) {
            return [];
        }

        Cache::put($key, $value, $this->cacheTime());

        return $value;
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<array-key, mixed>
     */
    public function callWebsiteApi(string $url, array $options): array
    {
        return $this->requestWebsiteApi($url, $options) ?? [];
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<array-key, mixed>
     */
    public function callApi(string $url, array $options): array
    {
        return $this->request($url, $options) ?? [];
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<array-key, mixed>|null
     */
    private function requestWebsiteApi(string $url, array $options): ?array
    {
        return $this->request("websites/{$this->site_id}/{$url}", $options);
    }

    /**
     * A dashboard must not fall over because Umami is slow, down or returns
     * something unexpected, so every failure becomes null.
     *
     * @param  array<string, mixed>  $options
     * @return array<array-key, mixed>|null
     */
    private function request(string $url, array $options): ?array
    {
        try {
            $response = $this->http->get($url, $options);
        } catch (Throwable) {
            return null;
        }

        if (! $response->ok()) {
            return null;
        }

        $json = $response->json();

        return is_array($json) ? $json : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function getDefaultOptions(UmamiWebsiteStats $endpoint, Filter $filter): array
    {
        $timezone = config('app.timezone');

        $defaultOptions = [
            'stats' => [
                'unit' => 'day', // minute, hour, day, month, year
                'url' => null,
                'referrer' => null,
                'title' => null,
                'os' => null,
                'browser' => null,
                'device' => null,
                'country' => null,
                'region' => null,
                'city' => null,
            ],
            'pageviews' => [
                'unit' => 'day',
                'timezone' => $timezone,
                'url' => null,
                'referrer' => null,
                'pageTitle' => null,
                'os' => null,
                'browser' => null,
                'device' => null,
                'country' => null,
                'region' => null,
                'city' => null,
            ],
            'events' => [
                'unit' => 'day',
                'timezone' => $timezone,
                'url' => null,
                'eventName' => null,
            ],
            'metrics' => [
                'type' => 'url', // url | referrer | browser | os | device | country | event
                'unit' => 'day',
                'timezone' => $timezone,
                'url' => null,
                'referrer' => null,
                'pageTitle' => null,
                'os' => null,
                'browser' => null,
                'device' => null,
                'country' => null,
                'region' => null,
                'city' => null,
            ],
            'active' => [],
        ];

        $filterOptions = [
            'startAt' => $filter->from->getTimestampMs(),
            'endAt' => $filter->to->getTimestampMs(),
            'limit' => $filter->getLimit(),
        ];

        return array_merge($defaultOptions[$endpoint->value], $filterOptions);
    }

    /**
     * Returns a valid token, or null when Umami cannot be reached. Wrong
     * credentials are a configuration error and throw, like a missing setting.
     *
     * @throws Exception when Umami rejects the credentials
     */
    private function getToken(string $username, string $password): ?string
    {
        $cacheKey = $this->cachePrefix . 'token:' . md5($username);

        $token = Cache::get($cacheKey);

        if (is_string($token) && $token !== '') {
            try {
                if ((clone $this->http)->withToken($token)->post('/auth/verify')->ok()) {
                    return $token;
                }
            } catch (Throwable) {
                // Verification failed, log in again below.
            }
        }

        try {
            $response = $this->http->post('/auth/login', [
                'username' => $username,
                'password' => $password,
            ]);
        } catch (Throwable) {
            return null;
        }

        if ($response->serverError()) {
            return null;
        }

        $token = $response->json('token');

        if (! $response->ok() || ! is_string($token) || $token === '') {
            throw new Exception('Could not authenticate with Umami, check the username and password.');
        }

        Cache::put($cacheKey, $token, $this->cacheTime());

        return $token;
    }

    private function cacheTime(): int
    {
        return max((int) self::configString('cache_time', '300'), 1);
    }

    private static function configString(string $key, string $default = ''): string
    {
        $value = config("filament-umami-widgets.{$key}");

        if (is_string($value) || is_int($value) || is_float($value)) {
            return trim((string) $value);
        }

        return $default;
    }

    private static function toInt(mixed $value): int
    {
        return is_numeric($value) ? (int) $value : 0;
    }
}
