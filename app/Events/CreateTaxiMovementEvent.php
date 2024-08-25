<?php

namespace App\Events;


use App\Models\Movement;
use App\Models\UserProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

class CreateMovementEvent extends BaseEvent
{
    protected $time;
    public function __construct($Movement)
    {
        parent::__construct($Movement);
        $this->time = $Movement->created_at;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('Taxi-movement.' . getAdminId())];

        // new Channel('channel');
    }

    public function broadcastWith(): array
    {

        $customer = UserProfile::where('user_id', $this->customer_id)->select('name', 'avatar', 'phone_number')->first();
        return [
            'index' => Movement::where('is_don', false)->count(),
            'drivers' => getReadyDrivers(),
            'request_id' => $this->request_id,
            'customer' => $customer,
            'gender' => $this->gender,
            'customer_address' => $this->customer_address,
            'distnation_address' => $this->customer_destnation_address,
            'lat' => $this->location_lat,
            'long' => $this->location_long,
            'time' => date('Y-m-d -- h:i A', strtotime($this->time))
        ];
    }

    // public function broadcastAs(){
    //     return 'admin-chanel';
    // }
}
