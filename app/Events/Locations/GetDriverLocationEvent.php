<?php

namespace App\Events\Locations;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GetDriverLocationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $driver;
    protected $receiver;

    /**
     * Create a new event instance.
     */
    public function __construct(User $receiver, User $driver)
    {
        $this->driver = $driver;
        $this->receiver = $receiver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('driver-location.' . $this->receiver->id),
        ];
    }

    /**
     * @return array of broadcasting data
     */
    public function broadcastWith()
    {
        return [
            'driver_name' => $this->driver->profile->name,
            'driver_avatar' => $this->driver->profile->avatar,
            'latitude' => $this->driver->last_location_latitude,
            'longitude' => $this->driver->last_location_longitude
        ];
    }

    /**
     * @return string Channel name to listen in frontend
     */
    public function broadcastAs()
    {
        return 'getDriverLocationEvent';
    }
}
