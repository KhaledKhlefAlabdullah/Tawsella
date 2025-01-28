<?php

namespace App\Events\Movements;

use App\Enums\UserEnums\UserGender;
use App\Models\TaxiMovement;
use Illuminate\Broadcasting\InteractsWithSockets;
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
        return [
            'customer.' . $this->taxiMovement->customer_id,
            'driver.' . $this->taxiMovement->driver_id
        ];
    }



    /**
     * Data to broadcast with the driver.
     *
     * @return array
     */
    public function broadcastWithDriver(): array
    {
        $customer_profile = $this->taxiMovement->customer ? $this->taxiMovement->customer->profile : null;

        return [
            'request_id' => $this->taxiMovement->id,
            'customer' => $customer_profile,
            'taxiMovementInfo' => $this->getDriverData()
        ];
    }

    /**
     * Data to broadcast with the customer.
     *
     * @return array
     */
    public function broadcastWithCustomer(): array
    {
        $driver_profile = $this->taxiMovement->driver ? $this->taxiMovement->driver->profile : null;

        return [
            'request_id' => $this->taxiMovement->id,
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
        return [
            'customer' => $this->broadcastWithCustomer(),
            'driver' => $this->broadcastWithDriver()
        ];
    }

    /**
     * Get the event name to broadcast as.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'acceptRequest';
    }
}
