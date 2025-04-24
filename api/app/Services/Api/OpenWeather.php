<?php

namespace App\Services\Api;

use App\Events\UpdateWeatherErrorOccurred;
use App\Models\UserLocation;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

class OpenWeather
{
    private const CURRENT_WEATHER_ENDPOINT = '/data/2.5/weather';

    public function __construct(private Client $client) {
        // Do nothing.
    }

    /**
     * @param Collection<int,UserLocation> $userLocations
     * @return Collection<int,array>
     */
    public function getWeatherForGivenUserLocations(Collection $userLocations): Collection
    {
        $weatherRequests = $userLocations->mapWithKeys(
            fn (UserLocation $userLocation) => [
                $userLocation->userId => $this->composeWeatherRequest($userLocation->latitude, $userLocation->longitude)
            ]
        );

        $errors = 0;
        /** @var Collection<int,array> $results */
        $results = collect();
        $pool = new Pool($this->client, $weatherRequests->toArray(), [
            'concurrency' => 5,
            'fulfilled' => function (Response $response, $userId) use (&$results) {
                $results->put($userId, [
                    'status' => PromiseInterface::FULFILLED,
                    'value' => $response,
                ]);
            },
            'rejected' => function (RequestException $reason, $userId) use (&$errors, &$results) {
                $errors++;
                $results->put($userId, [
                    'status' => PromiseInterface::REJECTED,
                    'reason' => $reason->getMessage(),
                ]);
            },
        ]);

        if ($errors === $weatherRequests->count()) {
            UpdateWeatherErrorOccurred::dispatch('Could not load weather for any users!');
        }

        // Initiate the transfers and create a promise
        $promise = $pool->promise();
        // Force the pool of requests to complete.
        $promise->wait();

        return $results;
    }

    private function composeWeatherRequest(float $latitude, float $longitude): Request
    {
        $queryParams = [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => config('services.openweather.key'),
            'units' => 'imperial'
        ];

        return new Request('GET', self::CURRENT_WEATHER_ENDPOINT . '?' . http_build_query($queryParams));
    }
}
