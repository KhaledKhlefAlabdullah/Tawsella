<?php

namespace App\Events\Movements;

use App\Enums\UserEnums\UserGender;
use App\Models\TaxiMovement;
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
        return [
            'customer.' . $this->taxiMovement->customer_id,
            'driver.' . $this->taxiMovement->driver_id
        ];
    }

    /**
     * Get the driver-related data.
     *
     * @return array
     */
    public function getDriverData(): array
    {
        return [
            'gender' => UserGender::getKey($this->taxiMovement->gender),
            'customer_address' => $this->taxiMovement->start_address,
            'destination_address' => $this->taxiMovement->destination_address,
            'location_lat' => $this->taxiMovement->start_latitude,
            'location_long' => $this->taxiMovement->start_longitude,
            'type' => $this->taxiMovement->movement_type->type,
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
        return array_merge(
            $this->broadcastWithCustomer(),
            $this->broadcastWithDriver()
        );
    }

    /**
     * Get the event name to broadcast as.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'accept-request';
    }
}
