<?php

namespace App\Models;

class UserLocation
{
    public function __construct(
        public int $userId,
        public float $latitude,
        public float $longitude
    ) {
        // Do nothing.
    }
}
