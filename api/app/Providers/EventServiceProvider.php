<?php

namespace App\Providers;

use App\Events\UpdateWeather;
use App\Events\UpdateWeatherForUser;
use App\Listeners\DispatchUserWeatherUpdates;
use App\Listeners\TriggerUserWeatherUpdate;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UpdateWeather::class => [
            DispatchUserWeatherUpdates::class
        ],
        UpdateWeatherForUser::class => [
            TriggerUserWeatherUpdate::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
