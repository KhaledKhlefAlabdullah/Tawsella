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
    protected $customer_destnation_address;


    /**
     * Create a new event instance.
     */
    public function __construct($Movement) //$customer_id, $location_lat, $location_long,$gender,$customer_address,$customer_destnation_address)
    {
        $this->request_id = $Movement->id;
        $this->customer_id = $Movement->customer_id;
        $this->location_lat = $Movement->start_latitude;
        $this->location_long = $Movement->start_longitude;
        $this->gender = $Movement->gender;
        $this->customer_address = $Movement->my_address;
        $this->customer_destnation_address = $Movement->destnation_address;
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
