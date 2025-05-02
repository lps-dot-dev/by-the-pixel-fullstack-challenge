<?php

namespace App\Services\Api;

use App\Models\UserLocation;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class OpenWeather
{
    private const CURRENT_WEATHER_ENDPOINT = '/data/2.5/weather';

    public function __construct(private Client $client) {
        // Do nothing.
    }

    /** @throws GuzzleException */
    public function getWeatherForGivenUserLocation(UserLocation $userLocation): Response
    {
        $request = $this->composeWeatherRequest($userLocation->latitude, $userLocation->longitude);
        return $this->client->send($request);
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
