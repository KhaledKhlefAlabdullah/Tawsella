<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BaseEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request_id;
    protected $customer_id;
    protected $location_lat;
    protected $location_long;
    protected $gender;
    protected $customer_address;
    protected $customer_destination_address;


    /**
     * Create a new event instance.
     */
    public function __construct($taxiMovement) //$customer_id, $location_lat, $location_long,$gender,$customer_address,$customer_destination_address)
    {
        $this->request_id = $taxiMovement->id;
        $this->customer_id = $taxiMovement->customer_id;
        $this->location_lat = $taxiMovement->start_latitude;
        $this->location_long = $taxiMovement->start_longitude;
        $this->gender = $taxiMovement->gender;
        $this->customer_address = $taxiMovement->start_address;
        $this->customer_destination_address = $taxiMovement->destination_address;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
