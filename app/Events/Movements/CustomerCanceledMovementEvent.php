<?php

namespace App\Events\Movements;


use App\Models\TaxiMovement;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerCanceledMovementEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected TaxiMovement $taxiMovement;

    /**
     * Create a new event instance.
     */
    public function __construct(TaxiMovement $taxiMovement)
    {
        $this->taxiMovement = $taxiMovement;
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
            new PrivateChannel('user-canceled-movement-request.' . getAdminId()),
        ];
    }

    public function broadcastWith(): array
    {
        $customer = $this->taxiMovement->customer;
        return [
            'message' => $this->taxiMovement->state_message,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->profile?->name,
                'avatar' => $customer->profile?->avatar,
                'phone_number' => $customer->profile?->phone_number,
            ]
        ];
    }

    public function broadcastAs(): string
    {
        return 'canceled-transportation-service-request';
    }
}
