<?php

namespace App\Providers;

use App\Services\Api\OpenWeather;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this
            ->app
            ->when(OpenWeather::class)
            ->needs(Client::class)
            ->give(fn () => new Client(['base_uri' => 'https://api.openweathermap.org']));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
