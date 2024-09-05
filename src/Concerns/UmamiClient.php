<?php

namespace Schmeits\FilamentUmami\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Schmeits\FilamentUmami\Enums\UmamiMetricTypes;
use Schmeits\FilamentUmami\Enums\UmamiType;
use Schmeits\FilamentUmami\Enums\UmamiWebsiteStats;

class UmamiClient
{
    private PendingRequest | Http $http;

    private UmamiType $type;

    private string $baseUrl;

    private string $api_key;

    private ?string $site_id;

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function __construct()
    {
        $this->type = UmamiType::from(config('filament-umami-widgets.type', 'self-hosted'));

        $this->baseUrl = str(config('filament-umami-widgets.api_endpoint_url'))->rtrim('/');

        // minimum timout must be larger than 1 second
        $timeout = max(intval(config('filament-umami-widgets.timeout', 5)), 1);

        $this->http = Http::baseUrl($this->baseUrl)->timeout($timeout);

        if ($this->type === UmamiType::TYPE_SELF_HOSTED) {

            $username = config('filament-umami-widgets.username');
            $password = config('filament-umami-widgets.password');

            if (empty($username) || empty($password)) {
                throw new \Exception('Umami username and password are required.');
            }

            $token = $this->getToken($username, $password);

            $this->http->withToken($token);

        } elseif ($this->type === UmamiType::TYPE_CLOUD) {

            $this->api_key = config('filament-umami-widgets.cloud_api_key');

            if (empty($this->api_key)) {
                throw new \Exception('Umami api_key is required.');
            }

            $this->http->withHeader('x-umami-api-key', $this->api_key);

        }

        $this->site_id = config('filament-umami-widgets.website_id');

        throw_if(empty($this->site_id), new \Exception('Umami website ID is required.'));
    }

    public function getActive(Filter $filter): int
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_ACTIVE, $filter);

        $value = $this->callWebsiteApi('active', $options);

        return $value['x'] ?? 0;
    }

    public function getStats(Filter $filter): array
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_STATS, $filter);

        return $this->getCachedValue('get-stats-' . $filter->hash(), function () use ($options) {
            return $this->callWebsiteApi('stats', $options);
        });
    }

    public function getPageViewsAndSessions(): array
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_PAGEVIEWS, new Filter);

        return $this->getCachedValue('get-pageviews', function () use ($options) {
            return $this->callWebsiteApi('pageviews', $options);
        });
    }

    public function getWebsiteEvents(Filter $filter): array
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_EVENTS, $filter);

        return $this->getCachedValue('get-events-' . $filter->hash(), function () use ($options) {
            return $this->callWebsiteApi('events', $options);
        });
    }

    public function getMetrics(Filter $filter, UmamiMetricTypes $type): array
    {
        $options = $this->getDefaultOptions(UmamiWebsiteStats::STAT_METRICS, $filter);

        $options['type'] = $type->value;

        return $this->getCachedValue('get-metrics-' . $type->value . '-' . $filter->hash(), function () use ($options) {
            return $this->callWebsiteApi('metrics', $options);
        });
    }

    private function getCachedValue(string $key, \Closure $callback): mixed
    {
        return Cache::remember($key, config('filament-umami-widgets.cache_time'), $callback);
    }

    public function callWebsiteApi(string $url, array $options): array
    {
        return $this->callApi("websites/{$this->site_id}/{$url}", $options);
    }

    public function callApi(string $url, array $options): array
    {
        $response = $this->http->get($url, $options);

        if ($response->ok()) {
            return $response->json();
        }

        return [];
    }

    private function getDefaultOptions(UmamiWebsiteStats $endpoint, Filter $filter): array
    {
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
                'timezone' => config('app.timezone'),
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
                'timezone' => config('app.timezone'),
                'url' => null,
                'eventName' => null,
            ],
            'metrics' => [
                'type' => 'url', // url | referrer | browser | os | device | country | event
                'unit' => 'day',
                'timezone' => config('app.timezone'),
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

        // default date options
        $filterOptions = [
            'startAt' => $filter->from->getTimestampMs(),
            'endAt' => $filter->to->getTimestampMs(),
            'limit' => $filter->getLimit(),
        ];

        return array_merge(
            $defaultOptions[$endpoint->value], // options based on endpoint
            $filterOptions // filter options
        );
    }

    /**
     * @throws \Exception
     */
    private function getToken(string $username, string $password): string
    {
        $token = Cache::get('umami-token');

        try {
            // check existing token
            if ($token) {
                $response = $this->http->withToken($token)->post('/auth/verify');
                if ($response->ok()) {
                    $this->cacheToken($token);

                    return $token;
                }
            }
        } catch (\Exception $exception) {
            //
        }

        // no token, fetch it
        $response = $this->http->post('/auth/login', [
            'username' => $username,
            'password' => $password,
        ]);

        if (! $response->ok()) {
            throw new \Exception('Could not authenticate.');
        }

        $token = $response->json()['token'];
        $this->cacheToken($token);

        return $token;
    }

    private function cacheToken(string $token): void
    {
        Cache::set('umami-token', $token, config('filament-umami-widgets.cache_time', 300));
    }
}
