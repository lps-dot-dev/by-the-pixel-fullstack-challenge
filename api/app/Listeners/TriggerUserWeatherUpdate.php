<?php

namespace App\Listeners;

use App\Events\UpdateWeatherFinished;
use App\Events\UpdateWeatherForUser;
use App\Events\UpdateWeatherForUserFinished;
use App\Models\UserWeather as UserWeatherData;
use App\Services\UserWeather;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class TriggerUserWeatherUpdate implements ShouldQueue
{
    private const USERS_PROCESSED_CACHE_KEY_POSTFIX = '_PROCESSED';
    private const USER_WEATHER_CACHE_KEY_PREFIX = 'USER_WEATHER_';

    /**
     * Create the event listener.
     */
    public function __construct(private UserWeather $userWeatherService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UpdateWeatherForUser $event): void
    {
        $promise = $this->userWeatherService->fetchCurrentWeatherForUser($event->getUser());
        $promise->then(
            // $onFulfilled
            function (UserWeatherData $userWeather) {
                /** @todo We can probably remove alot of the data that we don't need to save memory */
                Cache::put(
                    self::USER_WEATHER_CACHE_KEY_PREFIX . $userWeather->userId,
                    $userWeather->weatherDataJson(),
                    now()->addHour()
                );

                UpdateWeatherForUserFinished::dispatch($userWeather);
            },
            // $onRejected
            function ($reason) {
                /** @todo announce that weather failed to update for given user */
            }
        );

        $usersProcessedRedisKey = $event->getUuid() . self::USERS_PROCESSED_CACHE_KEY_POSTFIX;
        /**
         * @var int $usersProcessed
         * @see https://redis.io/docs/latest/commands/incr/#pattern-counter
         * The cache abstraction is configured to use Redis. This means that `increment` is an atomic operation,
         * thus we do not need to worry about race conditions among concurrent queue workers.
         */
        $usersProcessed = Cache::increment($usersProcessedRedisKey);
        if ($usersProcessed === $event->getTotalUsers()) {
            Cache::delete($usersProcessedRedisKey);
            UpdateWeatherFinished::dispatch();
        }
    }
}
