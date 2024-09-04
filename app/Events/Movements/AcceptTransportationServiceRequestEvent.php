<?php

namespace App\Events\Movements;

use App\Models\Movement;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptTransportationServiceRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Movement $movement;

    /**
     * Create a new event instance.
     */
    public function __construct(Movement $movement)
    {
        $this->movement = $movement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('accept-movement-request.' . $this->movement->customer_id),
        ];
    }

    public function broadcastWith(): array
    {
        $driver = getAndCheckModelById(User::class, $this->movement->driver_id);
        return [
            'message' => $this->movement->state_message,
            'driver' => [
                'id' => $driver->id,
                'name' => $driver->profile->name,
                'avatar' => $driver->profile->avatar,
                'phone_number' => $driver->profile->phone_number,
            ]
        ];
    }

    public function broadcastAs(): string
    {
        return 'transportation-service-accepted';
    }
}
