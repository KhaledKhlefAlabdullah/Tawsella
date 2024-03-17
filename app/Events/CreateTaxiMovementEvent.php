<?php

namespace App\Events;

use App\Models\UserProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateTaxiMovementEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $customer_id;
    protected $location_lat;
    protected $location_long;

    /**
     * Create a new event instance.
     */
    public function __construct($customer_id, $location_lat, $location_long)
    {
        $this->customer_id = $customer_id;
        $this->location_lat = $location_lat;
        $this->location_long = $location_long;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn() : Channel 
    {
        return new PrivateChannel('Taxi-movement.'.getAdminId());

        // new Channel('channel');
    }

    public function broadcastWith(): array
    {       

        $customer = UserProfile::where('user_id',$this->customer_id)->select('name','user_avatar','phoneNumber')->first();
        return [
            'customer' => $customer,
            'location_lat' => $this->location_lat,
            'location_long' => $this->location_long,
        ];
    }

    // public function broadcastAs(){
    //     return 'admin-chanel';
    // }
}
