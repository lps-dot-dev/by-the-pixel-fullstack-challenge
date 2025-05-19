<?php

namespace App\Listeners;

use App\Events\UpdateWeather;
use App\Events\UpdateWeatherForUser;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DispatchUserWeatherUpdates implements ShouldQueue
{
    private UuidInterface $uuid;

    /**
     * Create the event listener.
     */
    public function __construct(private User $user)
    {
        $this->uuid = Uuid::uuid7();
    }

    /**
     * Handle the event.
     */
    public function handle(UpdateWeather $event): void
    {
        $users = $this->user->all();
        if ($users->isNotEmpty()) {
            $users->each(fn (User $user) => UpdateWeatherForUser::dispatch($this->uniqueId(), $users->count(), $user));
        }
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->uuid->toString();
    }
}
