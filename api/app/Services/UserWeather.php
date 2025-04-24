<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserLocation;
use App\Services\Api\OpenWeather;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserWeather
{
    private const USER_WEATHER_REDIS_KEY_PREFIX = 'USER_WEATHER_';

    public function __construct(private OpenWeather $openWeatherService)
    {
        // Do nothing.
    }

    /** @return Collection<int,\stdClass> */
    public function updateWeatherForAllUsers(): Collection
    {
        $users = User::all()->keyBy('id');
        $userLocations = $users->map(fn (User $user) => new UserLocation($user->id, $user->latitude, $user->longitude));
        /** @todo Check cache before attempting to pull updated weather */
        $results = $this->openWeatherService->getWeatherForGivenUserLocations($userLocations);

        foreach ($results as $userId => $result) {
            if ($result['status'] === PromiseInterface::REJECTED) {
                continue;
            }

            if (
                $result['status'] === PromiseInterface::FULFILLED
                && $result['value'] instanceof Response === false
            ) {
                /** @todo Handle unexpected values */
                continue;
            }

            /** @todo We can probably remove alot of the data that we don't need to save memory */
            Cache::put(
                self::USER_WEATHER_REDIS_KEY_PREFIX . $userId,
                (string)$result['value']->getBody(),
                now()->addHour()
            );
        }

        return $results
            ->filter(fn (array $result, int $_) => $result['status'] === PromiseInterface::FULFILLED)
            ->map(fn (array $result, int $_) => Utils::jsonDecode($result['value']->getBody()));
    }
}
