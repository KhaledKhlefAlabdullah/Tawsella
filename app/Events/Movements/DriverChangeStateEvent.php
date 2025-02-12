<?php

namespace App\Events\Movements;

use App\Models\User;
use http\Message;
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
    protected array $notification;
    /**
     * Create a new event instance.
     */
    public function __construct(User $driver, $notification)
    {
        $this->driver = $driver;
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new channel('driverChangeState.'.getAdminId()),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'driver_id' => $this->driver->id,
            'new_driver_state' => $this->driver->driver_state,
            'notification' => $this->notification,
        ];
    }

    public function broadcastAs(): string
    {
        return 'driverChangeState';
    }
}
