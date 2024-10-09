<?php

namespace App\Events\Movements;

use App\Enums\UserEnums\UserGender;
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
            'customer.' . $customer_id,
            'driver.' . $driver_id
        ];
    }

    public function getDriverData()
    {
        return [
            'gender' => UserGender::getKey($this->taxiMovement->gender),
            'customer_address' => $this->taxiMovement->start_address,
            'destination_address' => $this->taxiMovement->destination_address,
            'location_lat' => $this->taxiMovement->start_latitude,
            'location_long' => $this->taxiMovement->start_longitude,
            'type' => $this->taxiMovement->movement_type->type
        ];
    }

    public function broadcastWithDriver(): array
    {
        $customer_profile = $this->taxiMovement->customer ? $this->taxiMovement->customer->profile : null;

        return [
            'request_id' => $this->taxiMovement->id,
            'customer' => $customer_profile,
            'taxiMovementInfo' => $this->getDriverData()
        ];
    }

    public function broadcastWithCustomer(): array
    {
        $driver_profile = $this->taxiMovement->driver ? $this->taxiMovement->driver->profile : null;

        return [
            'message' => $this->taxiMovement->state_message,
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
        if (in_array('customer.' . $this->taxiMovement->customer_id, array_map(fn($channel) => $channel->name, $this->broadcastOn()))) {
            return $this->broadcastWithCustomer();
        }
        if (in_array('driver.' . $this->taxiMovement->driver_id, array_map(fn($channel) => $channel->name, $this->broadcastOn()))) {
            return $this->broadcastWithDriver();
        }

        return [];
    }


    public function broadcastAs()
    {
        return 'accept-request';
    }
}
