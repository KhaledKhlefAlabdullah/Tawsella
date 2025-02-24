<?php

namespace App\Events\Movements;

use App\Enums\UserEnums\UserGender;
use App\Models\TaxiMovement;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestingTransportationServiceEvent implements ShouldBroadcast
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
     * @return Channel[]
     */
    public function broadcastOn(): array
    {
        return[
            new Channel('TaxiMovement.'.getAdminId())
        ];
    }

    public function broadcastWith(): array
    {

        $customer = $this->taxiMovement->customer;
        return [
            'index' => TaxiMovement::where('is_redirected', false)->count(),
            'drivers' => User::getReadyDrivers(),
            'request_id' => $this->taxiMovement->request_id,
            'customer' => $customer,
            'gender' => UserGender::getKey($this->taxiMovement->gender),
            'customer_address' => $this->taxiMovement->customer_address,
            'destination_address' => $this->taxiMovement->customer_destination_address,
            'start_latitude' => $this->taxiMovement->start_latitude,
            'start_longitude' => $this->taxiMovement->start_longitude,
            'time' => date('Y-m-d -- h:i A', strtotime($this->taxiMovement->created_at))
        ];
    }

    public function broadcastAs(): string
    {
        return 'requestingTransportationService';
    }
}
