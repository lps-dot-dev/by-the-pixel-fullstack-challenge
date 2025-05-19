<?php

namespace App\Http\Controllers;

use App\Events\UpdateWeather;

class WeatherController extends Controller
{
    public function index() {
        UpdateWeather::dispatch();
    }
}
