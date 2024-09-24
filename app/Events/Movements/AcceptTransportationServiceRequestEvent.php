<?php

namespace App\Events\Movements;

use App\Models\TaxiMovement;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptTransportationServiceRequestEvent implements ShouldBroadcast
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
        $customer_id = $this->taxiMovement->customer_id;
        $driver_id = $this->taxiMovement->driver_id;

        return [
            new PrivateChannel('customer.' . $customer_id),
            new PrivateChannel('driver.' . $driver_id)        ];
    }

    public function getDriverData()
    {
        return [
            'gender' => $this->taxiMovement->gender,
            'customer_address' => $this->taxiMovement->start_address,
            'destination_address' => $this->taxiMovement->destination_address,
            'location_lat' => $this->taxiMovement->start_latitude,
            'location_long' => $this->taxiMovement->start_longitude,
            'type' => $this->taxiMovement->movement_type->type
        ];
    }

    public function broadcastWithDriver(): array
    {
        $customer_profile = $this->taxiMovement->customer()->profile;

        return [
            'request_id' => $this->taxiMovement->id,
            'customer' => $customer_profile,
            'taxiMovementInfo' => $this->getDriverData()
        ];
    }

    public function broadcastWithCustomer(): array
    {
        $driver_profile = $this->taxiMovement->driver()->profile;
        return [
            'message' => 'accepted',
            'driver' => $driver_profile
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        $channels = $this->broadcastOn();

        // Check which channel is being broadcast to
        foreach ($channels as $channel) {
            if ($channel instanceof PrivateChannel && strpos($channel->name, 'customer') !== false) {
                return $this->broadcastWithCustomer();
            } elseif ($channel instanceof PrivateChannel && strpos($channel->name, 'driver') !== false) {
                return $this->broadcastWithDriver();
            }
        }

        // Default data if channel not recognized
        return [];
    }

    public function broadcastAs(){
        return 'accept-request';
    }
}
