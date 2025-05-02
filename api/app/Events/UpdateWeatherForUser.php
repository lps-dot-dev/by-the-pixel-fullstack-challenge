<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateWeatherForUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private string $uuid,
        private int $totalUsers,
        private User $user
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('weather');
    }

    public function broadcastAs(): string
    {
        return 'user_updating';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return ['userId' => $this->user->id];
    }

    public function getTotalUsers(): int
    {
        return $this->totalUsers;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
