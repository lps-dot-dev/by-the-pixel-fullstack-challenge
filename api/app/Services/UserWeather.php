<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserLocation;
use App\Models\UserWeather as UserWeatherData;
use App\Services\Api\OpenWeather;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Utils;
use Throwable;

class UserWeather
{
    public function __construct(private OpenWeather $openWeatherService)
    {
        // Do nothing.
    }

    /**
     * @param User $user
     * @return FulfilledPromise<App\Models\UserWeather>|RejectedPromise<string> A fulfilled promise will contain the user weather data model,
     * the rejected promise will contain the exception message. Since this promise is already fulfilled or rejected,
     * any declared callbacks will immediately trigger and "waiting" on this promise is not necessary.
     */
    public function fetchCurrentWeatherForUser(User $user): FulfilledPromise|RejectedPromise
    {
        $userLocation = new UserLocation($user->id, $user->latitude, $user->longitude);
        try {
            $result = $this->openWeatherService->getWeatherForGivenUserLocation($userLocation);
            $weatherData = Utils::jsonDecode($result->getBody());
        } catch (Throwable $e) {
            // Do something.
            return new RejectedPromise($e->getMessage());
        }

        return new FulfilledPromise(new UserWeatherData($user->id, $weatherData));
    }
}
