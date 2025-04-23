<?php

namespace App\Http\Controllers;

use App\Events\UpdateWeather;
use App\Services\UserWeather;

class WeatherController extends Controller
{
    public function index(UserWeather $userWeatherService) {
        event(new UpdateWeather());
        /** @todo Use an event driven approach and turn this into a queue job */
        return response()->json($userWeatherService->updateWeatherForAllUsers());
    }
}
