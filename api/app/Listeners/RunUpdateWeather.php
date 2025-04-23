<?php

namespace App\Listeners;

use App\Events\UpdateWeather;
use App\Events\UpdateWeatherFinished;
use App\Services\UserWeather;
use Illuminate\Contracts\Queue\ShouldQueue;

class RunUpdateWeather implements ShouldQueue
{
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
    public function handle(UpdateWeather $event): void
    {
        $weatherData = $this->userWeatherService->updateWeatherForAllUsers();
        UpdateWeatherFinished::dispatch($weatherData);
    }
}
