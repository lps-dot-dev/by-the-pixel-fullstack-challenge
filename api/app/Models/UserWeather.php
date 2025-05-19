<?php

namespace App\Models;

use GuzzleHttp\Utils;

class UserWeather
{
    public function __construct(
        public int $userId,
        public \stdClass $weatherData
    ) {
        // Do nothing.
    }

    public function weatherDataJson(): string
    {
        if (empty($this->weatherData)) {
            return '{}';
        }
        
        try {
            return Utils::jsonEncode($this->weatherData);
        } catch (\InvalidArgumentException $_) {
            return '{}';
        }
    }
}
