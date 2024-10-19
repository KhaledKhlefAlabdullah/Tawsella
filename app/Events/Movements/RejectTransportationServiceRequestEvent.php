<?php

namespace App\Events\Movements;

use App\Models\TaxiMovement;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RejectTransportationServiceRequestEvent implements ShouldBroadcast
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
            'customer-channel.' . $this->taxiMovement->customer_id,
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->taxiMovement->state_message,
        ];
    }

    public function broadcastAs(): string
    {
        return 'transportation-service-rejected';
    }
}
