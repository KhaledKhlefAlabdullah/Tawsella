<?php

namespace App\Events\Movements;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverChangeStateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected User $driver;
    /**
     * Create a new event instance.
     */
    public function __construct(User $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('driver-change-state.'.getAdminId()),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'driver_id' => $this->driver->id,
            'new_driver_state' => $this->driver->driver_state
        ];
    }

    public function broadcastAs(): string
    {
        return 'Driver-change-state';
    }
}
