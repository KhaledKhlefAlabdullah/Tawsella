<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GetTaxiLocationsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $driver_id;
    protected $lat;
    protected $long;

    /**
     * Create a new event instance.
     */
    public function __construct($driver_id, $lat, $long)
    {
        $this->driver_id = $driver_id;
        $this->lat = $lat;
        $this->long = $long;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return int, \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn(): Channel
    {
        return  new PrivateChannel('TaxiLocation.'. getAdminId());
    }

    public function broadcastWith(): array
    {
        return [
            'driver_id' => $this->driver_id,
            'lat' => $this->lat,
            'long' => $this->long
        ];
    }

    // public function broadcastAs(){
    //     return 'TaxiLocation';
    // }
}
