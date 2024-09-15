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

class RequestingTransportationServiceEvent implements ShouldBroadcast
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
            new PrivateChannel('movement-request-to.' . $this->movement->driver_id),
        ];
    }

    public function broadcastWith(): array
    {
        $customer = getAndCheckModelById(User::class, $this->movement->customer_id);
        return [
            'movement' => [
                'movement_id' => $this->movement->id,
                'start_address' => $this->movement->start_address,
                'destination_address' => $this->movement->destination_address,
                'start_latitude' => $this->movement->start_latitude,
                'start_longitude' => $this->movement->start_longitude,
                'end_latitude' => $this->movement->end_latitude,
                'end_longitude' => $this->movement->end_longitude,
                'is_onKM' => $this->movement->is_onKM
            ],
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
        return 'requesting-transportation-service';
    }
}
