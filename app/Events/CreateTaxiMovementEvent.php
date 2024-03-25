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

class CreateTaxiMovementEvent extends BaseEvent
{
    public function __construct($taxiMovement ){
        parent::__construct($taxiMovement);
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn() : array 
    {
        return [new PrivateChannel('Taxi-movement.'.getAdminId())];

        // new Channel('channel');
    }

    public function broadcastWith(): array
    {       

        $customer = UserProfile::where('user_id',$this->customer_id)->select('name','user_avatar','phoneNumber')->first();
        return [
            'request_id' => $this->request_id,
            'customer' => $customer,
            'gender' => $this->gender,
            'customer_address' => $this->customer_address,
            'destnation_address' => $this->customer_destnation_address,
            'location_lat' => $this->location_lat,
            'location_long' => $this->location_long,
        ];
    }

    // public function broadcastAs(){
    //     return 'admin-chanel';
    // }
}
