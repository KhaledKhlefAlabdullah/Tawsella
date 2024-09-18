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

class CustomerCanceledMovementEvent implements ShouldBroadcast
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
            new PrivateChannel('user-canceled-movement-request.' . $this->movement->driver_id),
        ];
    }

    public function broadcastWith(): array
    {
        $customer = getAndCheckModelById(User::class, $this->movement->customer_id);
        return [
            'message' => $this->movement->state_message,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->profile->name,
                'avatar' => $customer->profile->avatar,
                'phone_number' => $customer->profile->phone_number,
            ]
        ];
    }

    public function broadcastAs(): string
    {
        return 'canceled-transportation-service-request';
    }
}
